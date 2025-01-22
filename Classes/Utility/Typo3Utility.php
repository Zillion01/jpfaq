<?php

namespace Jp\Jpfaq\Utility;

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class Typo3Utility
{
    /**
     * Get TypoScript settings for a pluginSignature, e.g. jpfaq_faq
     *
     * @param string $pluginSignature
     * @return array
     */
    public static function getSettings(string $pluginSignature): array
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $fullTypoScript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $plainTypoScript = $typoScriptService->convertTypoScriptArrayToPlainArray($fullTypoScript);

        return $plainTypoScript['plugin']['tx_' . strtolower($pluginSignature)]['settings'] ?? [];
    }
}
