<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

$pluginSignature = ExtensionUtility::registerPlugin(
    'Jpfaq',
    'Faq',
    'jpFAQ',
    'ext-jpfaq-wizard-icon',
    'Plugins',
    'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:pi1_plus_wiz_description'
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $pluginSignature,
    'after:subheader',
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:' . 'jpfaq' . '/Configuration/FlexForm/Flexform.xml',
    $pluginSignature,
);

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'jpFAQ',
    'jpfaq',
    'apps-pagetree-folder-contains-board',
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-jpfaq'] = 'apps-pagetree-folder-contains-board';
