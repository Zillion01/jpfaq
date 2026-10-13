<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::registerPlugin(
    'Jpfaq',
    'Faq',
    'jpFAQ',
    'ext-jpfaq-wizard-icon',
    'plugins',
    'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:pi1_plus_wiz_description',
    'FILE:EXT:jpfaq/Configuration/FlexForm/Flexform.xml'
);
