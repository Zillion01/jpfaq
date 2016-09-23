<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Jp.Jpfaq',
            'Faq',
            [
                'Question' => 'list'
            ],
            // non-cacheable actions
            [
                'Question' => '',
                'Category' => ''
            ]
        );

    },
    $_EXTKEY
);

if (TYPO3_MODE === 'BE') {
    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'ext-jpfaq-wizard-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:jpfaq/Resources/Public/Icons/ce_wiz.gif']
    );
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE: EXT:jpfaq/Configuration/TSconfig/contentElementWizard.ts">');
