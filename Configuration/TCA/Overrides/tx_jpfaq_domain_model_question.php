<?php
defined('TYPO3_MODE') or die();

$boot = static function () {
    // Remove TCA settings for version 10+ to avoid entries in TCA migration check
    if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class)->getMajorVersion() >= 10) {
        foreach (['question', 'category', 'questioncomment', 'categorycomment'] as $tableSuffix) {
            unset($GLOBALS['TCA']['tx_jpfaq_domain_model_' . $tableSuffix]['interface']['showRecordFieldList']);
        }
    }
};

$boot();
unset($boot);