<?php

namespace Jp\Jpfaq\Utility;

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;

/**
 * Class: Typo3Utility
 * Description: general utilities
 *
 * 2024 Jacco van der Post <jacco@typo3-webdesign.nl>
 */
class Typo3Utility
{
    /**
     * Get typoscript settings for a plugin
     *
     * @param string $plugin
     *
     * @return mixed
     */
    public static function getSettings(string $plugin): mixed
    {
        $plugin = htmlspecialchars($plugin);
        $configurationManager = GeneralUtility::makeInstance(BackendConfigurationManager::class);
        $typoScriptSettings = $configurationManager->getTypoScriptSetup();

        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $typoScriptSettingsWithoutDots = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptSettings);

        return $typoScriptSettingsWithoutDots['plugin'][$plugin]['settings'];
    }
}
