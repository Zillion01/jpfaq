<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jpfaq_domain_model_question',
    'EXT:jpfaq/Resources/Private/Language/locallang_csh_tx_jpfaq_domain_model_question.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jpfaq_domain_model_question');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jpfaq_domain_model_category',
    'EXT:jpfaq/Resources/Private/Language/locallang_csh_tx_jpfaq_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jpfaq_domain_model_category');
