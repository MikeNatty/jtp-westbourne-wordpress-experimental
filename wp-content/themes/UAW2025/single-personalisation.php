<?php

use Timber\Timber;

$context = Timber::context();

if (isset($_GET['final_answers'])) {
    $final_answers = $_GET['final_answers'];
    $context['final_answers'] = $final_answers;
}

Timber::render('templates/single-personalisation.twig', $context);
