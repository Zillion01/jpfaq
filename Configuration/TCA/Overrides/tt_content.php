<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Jpfaq',
    'Faq',
    'jpFAQ'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jpfaq', 'Configuration/TypoScript', 'jpFAQ');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jpfaq_domain_model_question',
    'EXT:jpfaq/Resources/Private/Language/locallang_csh_tx_jpfaq_domain_model_question.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jpfaq_domain_model_question');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jpfaq_domain_model_category',
    'EXT:jpfaq/Resources/Private/Language/locallang_csh_tx_jpfaq_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jpfaq_domain_model_category');

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




