<?php

use Timber\Timber;
use Flynt\Utils\Options;

$context = Timber::context();

$contenthubGlobalOptions = Options::getGlobal('ContentHub');
if (!empty($contenthubGlobalOptions)) {
    $contenthubArchivePageId = $contenthubGlobalOptions['contenthubArchivePage'];
}

if ($contenthubArchivePageId) {
    $context['post'] = Timber::get_post($contenthubArchivePageId);
}

Timber::render('templates/archive-contenthub.twig', $context);
