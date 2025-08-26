<?php

namespace Flynt\ImageOptions;

use Flynt\Utils\Options;

Options::addTranslatable('ImageOptions', [
    [
        'label' => __('Placeholder image', 'flynt'),
        'instructions' => __('Fallback image to be displayed on cards when no other image is selected.<br>Image-Format: JPG, PNG, WebP.', 'flynt'),
        'name' => 'placeholderImage',
        'type' => 'image',
        'preview_size' => 'medium',
        'mime_types' => 'jpg,jpeg,png,svg,webp',
        'required' => 1,
    ],
]);
