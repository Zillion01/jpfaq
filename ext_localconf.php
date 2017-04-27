<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {

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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE: EXT:jpfaq/Configuration/TypoScript/TSconfig/includePageTSconfig.ts">');

/**
 * Wizard icon
 */
/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-jpfaq-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:jpfaq/Resources/Public/Icons/ce_wiz.svg']
);

/**
 *  Icon registry for record types
 */
$recordTypes = [
    'tx_jpfaq_domain_model_category',
    'tx_jpfaq_domain_model_question'
];

foreach ($recordTypes as $recordType) {
    $iconRegistry->registerIcon(
        'mimetypes-x-' . $recordType,
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:jpfaq/Resources/Public/Icons/' . $recordType . '.gif']
    );
}
