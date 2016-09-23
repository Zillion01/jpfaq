<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Jp.Jpfaq',
            'Faq',
            'jpFAQ'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'jpFAQ');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jpfaq_domain_model_question', 'EXT:jpfaq/Resources/Private/Language/locallang_csh_tx_jpfaq_domain_model_question.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jpfaq_domain_model_question');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jpfaq_domain_model_category', 'EXT:jpfaq/Resources/Private/Language/locallang_csh_tx_jpfaq_domain_model_category.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jpfaq_domain_model_category');

    },
    $_EXTKEY
);

// Add this 'page contains jpFAQ + icon' in FAQfolder
$TCA['pages']['columns']['module']['config']['items'][] = array('jpFAQ', 'jpfaq', 'apps-pagetree-folder-contains-board');

// Add faq icon in page tree
$TCA['pages']['ctrl']['typeicon_classes']['contains-jpfaq'] = 'apps-pagetree-folder-contains-board';
