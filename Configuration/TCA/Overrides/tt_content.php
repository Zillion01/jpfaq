<?php
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Jpfaq',
    'Faq',
    'jpFAQ'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['jpfaq_faq'] = 'recursive,select_key';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jpfaq_faq'] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'jpfaq_faq',
    'FILE:EXT:' . 'jpfaq' . '/Configuration/FlexForm/Flexform.xml'
);

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'jpFAQ',
    'jpfaq',
    'apps-pagetree-folder-contains-board'
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-jpfaq'] = 'apps-pagetree-folder-contains-board';
