<?php

use Timber\Timber;
use Flynt\Utils\Options;

$context = Timber::context();

$contenthubGlobalOptions = Options::getGlobal('Events');
if (!empty($contenthubGlobalOptions)) {
    $eventsArchivePageId = $contenthubGlobalOptions['eventsArchivePage'];
}

if ($eventsArchivePageId) {
    $context['post'] = Timber::get_post($eventsArchivePageId);
}

Timber::render('templates/archive-events.twig', $context);
