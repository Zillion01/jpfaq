<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['jpfaq_faq'] = 'recursive,select_key';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jpfaq_faq']='pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'jpfaq_faq',
    'FILE:EXT:jpfaq/Configuration/FlexForm/Flexform.xml'
);