<?php
// Add admin menu for Service Area import
add_action('admin_menu', 'service_area_import_menu');

function service_area_import_menu() {
    add_submenu_page(
        'edit.php?post_type=service-area',
        'Import Service Areas',
        'Import CSV',
        'manage_options',
        'service-area-import',
        'service_area_import_page'
    );
}

// Admin page content
function service_area_import_page() {
    ?>
    <div class="wrap">
        <h1>Service Area Import</h1>
        
        <?php
        // Handle form submissions
        if (isset($_POST['import_csv']) && isset($_FILES['csv_file'])) {
            service_area_process_import();
        }
        
        if (isset($_POST['delete_all_service_areas'])) {
            service_area_delete_all();
        }
        ?>
        
        <div style="background: white; padding: 20px; margin: 20px 0; border: 1px solid #ccd0d4;">
            <h2>Import CSV File</h2>
            <p>Upload a CSV file with columns: Location ID, Postcode, Service ID</p>
            
            <form method="post" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th scope="row">CSV File</th>
                        <td>
                            <input type="file" name="csv_file" accept=".csv" required>
                            <p class="description">Select CSV file to import</p>
                        </td>
                    </tr>
                </table>
                
                <?php wp_nonce_field('service_area_import', 'service_area_nonce'); ?>
                <p class="submit">
                    <input type="submit" name="import_csv" class="button-primary" value="Import CSV">
                </p>
            </form>
        </div>
        
        <div style="background: #fff2f2; padding: 20px; margin: 20px 0; border: 1px solid #ff6b6b;">
            <h2 style="color: #d63638;">Danger Zone</h2>
            <p>This will permanently delete ALL Service Area posts and cannot be undone.</p>
            
            <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL Service Area posts? This cannot be undone!');">
                <?php wp_nonce_field('delete_service_areas', 'delete_nonce'); ?>
                <p class="submit">
                    <input type="submit" name="delete_all_service_areas" class="button-secondary" value="Delete All Service Areas" style="background: #d63638; border-color: #d63638; color: white;">
                </p>
            </form>
        </div>
    </div>
    <?php
}

// Process CSV import
function service_area_process_import() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['service_area_nonce'], 'service_area_import')) {
        echo '<div class="notice notice-error"><p>Security verification failed.</p></div>';
        return;
    }
    
    // Check file upload
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="notice notice-error"><p>File upload failed.</p></div>';
        return;
    }
    
    $file_path = $_FILES['csv_file']['tmp_name'];
    
    // Read and parse CSV
    $csv_data = array();
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        $header = fgetcsv($handle); // Skip header row
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 3) {
                $csv_data[] = array(
                    'location_id' => trim($data[0]),
                    'postcode' => trim($data[1]),
                    'service_id' => trim($data[2])
                );
            }
        }
        fclose($handle);
    }
    
    if (empty($csv_data)) {
        echo '<div class="notice notice-error"><p>No valid data found in CSV file.</p></div>';
        return;
    }
    
    // Group data by postcode
    $grouped_data = array();
    foreach ($csv_data as $row) {
        $postcode = $row['postcode'];
        if (!isset($grouped_data[$postcode])) {
            $grouped_data[$postcode] = array(
                'location_id' => $row['location_id'],
                'service_ids' => array()
            );
        }
        
        // Add service ID if not already present
        if (!in_array($row['service_id'], $grouped_data[$postcode]['service_ids'])) {
            $grouped_data[$postcode]['service_ids'][] = $row['service_id'];
        }
    }

    // echo '<pre>'; var_dump($grouped_data); echo '</pre>'; die();
    
    $created_count = 0;
    $updated_count = 0;
    $errors = array();
    
    // Process each postcode
    foreach ($grouped_data as $postcode => $data) {
        // Check if post already exists
        // $existing_post = get_posts(array(
        //     'post_type' => 'service-area',
        //     'post_title' => $postcode,
        //     'post_status' => 'any',
        //     'numberposts' => 1
        // ));
        
        // if (!empty($existing_post)) {
        //     // Update existing post
        //     $post_id = $existing_post[0]->ID;
        //     $updated_count++;
        // } else {
        //     // Create new post
        //     $post_data = array(
        //         'post_title' => $postcode,
        //         'post_type' => 'service-area',
        //         'post_status' => 'publish',
        //         'post_content' => ''
        //     );
            
        //     $post_id = wp_insert_post($post_data);
            
        //     if (is_wp_error($post_id)) {
        //         $errors[] = "Failed to create post for postcode: {$postcode}";
        //         continue;
        //     }
            
        //     $created_count++;
        // }
        $post_data = array(
            'post_title' => $postcode,
            'post_type' => 'service-area',
            'post_status' => 'publish',
            'post_content' => ''
        );
        
        $post_id = wp_insert_post($post_data);
        
        if (is_wp_error($post_id)) {
            $errors[] = "Failed to create post for postcode: {$postcode}";
            continue;
        }
        
        $created_count++;
        
        // Update ACF fields
        try {
            // Update provider field (single post_object)
            update_field('provider', intval($data['location_id']), $post_id);
            
            // Update services field (multiple post_object)
            $service_ids = array_map('intval', $data['service_ids']);
            update_field('services', $service_ids, $post_id);
            
        } catch (Exception $e) {
            $errors[] = "Failed to update ACF fields for postcode {$postcode}: " . $e->getMessage();
        }
    }
    
    // Display results
    $messages = array();
    
    if ($created_count > 0) {
        $messages[] = "Created {$created_count} new Service Area posts.";
    }
    
    if ($updated_count > 0) {
        $messages[] = "Updated {$updated_count} existing Service Area posts.";
    }
    
    if (!empty($messages)) {
        echo '<div class="notice notice-success"><p>' . implode(' ', $messages) . '</p></div>';
    }
    
    if (!empty($errors)) {
        echo '<div class="notice notice-error"><p>Errors occurred:</p><ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul></div>';
    }
}

// Delete all service area posts
function service_area_delete_all() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['delete_nonce'], 'delete_service_areas')) {
        echo '<div class="notice notice-error"><p>Security verification failed.</p></div>';
        return;
    }
    
    // Get all service-area posts
    $posts = get_posts(array(
        'post_type' => 'service-area',
        'post_status' => 'any',
        'numberposts' => -1
    ));
    
    $deleted_count = 0;
    $errors = array();
    
    foreach ($posts as $post) {
        $result = wp_delete_post($post->ID, true); // true = force delete (skip trash)
        
        if ($result) {
            $deleted_count++;
        } else {
            $errors[] = "Failed to delete post: {$post->post_title} (ID: {$post->ID})";
        }
    }
    
    if ($deleted_count > 0) {
        echo '<div class="notice notice-success"><p>Deleted ' . $deleted_count . ' Service Area posts.</p></div>';
    }
    
    if (!empty($errors)) {
        echo '<div class="notice notice-error"><p>Errors occurred:</p><ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul></div>';
    }
    
    if ($deleted_count === 0 && empty($errors)) {
        echo '<div class="notice notice-info"><p>No Service Area posts found to delete.</p></div>';
    }
}
?>