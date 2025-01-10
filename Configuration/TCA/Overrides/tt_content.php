<?php
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Jpfaq',
    'Faq',
    'jpFAQ'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', 'jpfaq_faq', 'after:subheader');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:' . 'jpfaq' . '/Configuration/FlexForm/Flexform.xml',
    'jpfaq_faq'
);

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'jpFAQ',
    'jpfaq',
    'apps-pagetree-folder-contains-board'
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-jpfaq'] = 'apps-pagetree-folder-contains-board';
