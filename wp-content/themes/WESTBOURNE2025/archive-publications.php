<?php

use Timber\Timber;
use Flynt\Utils\Options;

$context = Timber::context();

$contenthubGlobalOptions = Options::getGlobal('Publications');
if (!empty($contenthubGlobalOptions)) {
    $publicationsArchivePageId = $contenthubGlobalOptions['publicationsArchivePage'];
}

if ($publicationsArchivePageId) {
    $context['post'] = Timber::get_post($publicationsArchivePageId);
}

Timber::render('templates/archive-publications.twig', $context);
