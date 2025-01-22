<?php

use Jp\Jpfaq\Controller\CategorycommentController;
use Jp\Jpfaq\Controller\QuestioncommentController;
use Jp\Jpfaq\Controller\QuestionController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

$boot = static function (): void {
    ExtensionUtility::configurePlugin(
        'Jpfaq',
        'Faq',
        [
            QuestionController::class => 'list, helpfulness',
            QuestioncommentController::class => 'comment, addComment',
            CategorycommentController::class => 'comment, addComment',
        ],
        [
            QuestionController::class => 'helpfulness',
            QuestioncommentController::class => 'comment, addComment',
            CategorycommentController::class => 'comment, addComment',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
};

$boot();
unset($boot);
