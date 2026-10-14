<?php

defined('TYPO3') or die();

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'label' => 'jpFAQ',
    'value' => 'jpfaq',
    'icon' => 'apps-pagetree-folder-contains-board',
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-jpfaq'] = 'apps-pagetree-folder-contains-board';
