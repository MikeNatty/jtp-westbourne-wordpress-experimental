<?php

namespace UAW\Asset;

use Flynt\Utils\Asset;
use Flynt\ComponentManager;

require_once get_template_directory() . '/vendor/autoload.php';

\Dotenv\Dotenv::createUnsafeImmutable(get_template_directory())->load();


if (!defined('ABSPATH')) {
    exit;
}

// Enqueuing assets to WP theme with assumption using Vite as compiler
class ViteEnqueueAssets
{
    private static $instance = null;
    private $isDevMode;
    private $vitePort;
    private $wpEnv;

    public function __construct()
    {
        $this->wpEnv = getenv('WP_ENV') ?: 'development';
        $this->isDevMode = $this->wpEnv === 'development';
        $this->vitePort = getenv('VITE_PORT') ?: '5173';
        // Add both development scripts
        if ($this->isDevMode) {
            add_action('wp_head', [$this, 'addReactRefreshScript']);
            add_action('wp_footer', [$this, 'addReactDevEntry']);
        } else {
            // In production, only enqueue the built assets
            add_action('wp_enqueue_scripts', [$this, 'addReactProdEntry']);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addReactRefreshScript()
    {
        ?>
        <script type="module">
            import RefreshRuntime from 'http://localhost:<?php echo esc_attr($this->vitePort); ?>/@react-refresh'
            RefreshRuntime.injectIntoGlobalHook(window)
            window.$RefreshReg$ = () => {}
            window.$RefreshSig$ = () => (type) => type
            window.__vite_plugin_react_preamble_installed__ = true
        </script>
        <?php
    }

    public function addReactDevEntry()
    {
        ?>
        <script type="module" src="http://localhost:<?php echo esc_attr($this->vitePort); ?>/assets/apps/index.tsx"></script>
        <?php
    }

    public function addReactProdEntry()
    {

        wp_enqueue_script('react-app', Asset::requireUrl('assets/apps/index.tsx'), [], null);
        wp_script_add_data('react-app', 'module', true);
    }
}