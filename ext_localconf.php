<?php

defined('TYPO3') || die('Access denied.');

$boot = static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Jpfaq',
        'Faq',
        [
            \Jp\Jpfaq\Controller\QuestionController::class => 'list, helpfulness',
            \Jp\Jpfaq\Controller\QuestioncommentController::class => 'comment, addComment',
            \Jp\Jpfaq\Controller\CategorycommentController::class => 'comment, addComment',
        ],
        // non-cacheable actions
        [
            \Jp\Jpfaq\Controller\QuestionController::class => 'helpfulness',
            \Jp\Jpfaq\Controller\QuestioncommentController::class => 'comment, addComment',
            \Jp\Jpfaq\Controller\CategorycommentController::class => 'comment, addComment',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    /**
     *  Icon registry
     */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconPath = 'EXT:jpfaq/Resources/Public/Icons/';

    $svgIcons = [
        'ext-jpfaq-wizard-icon' => $iconPath . 'ce_wiz.svg',
        'tx_jpfaq_domain_model_questioncomment' => $iconPath . 'tx_jpfaq_domain_model_questioncomment.svg',
        'tx_jpfaq_domain_model_categorycomment' => $iconPath . 'tx_jpfaq_domain_model_categorycomment.svg',
    ];

    foreach ($svgIcons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => $path]
        );
    }

    $bitmapIcons = [
        'tx_jpfaq_domain_model_category' => $iconPath . 'tx_jpfaq_domain_model_category.gif',
        'tx_jpfaq_domain_model_question' => $iconPath . 'tx_jpfaq_domain_model_question.gif',
    ];

    foreach ($bitmapIcons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => $path]
        );
    }
};

$boot();
unset($boot);
