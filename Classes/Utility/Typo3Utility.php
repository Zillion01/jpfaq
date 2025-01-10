<?php

namespace Jp\Jpfaq\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;

class Typo3Utility
{
    /**
     * Get TypoScript settings
     *
     * @param string $extensionName
     * @return array
     */
    public static function getSettings(string $extensionName): array
    {
        /** @var ServerRequestInterface $request */
        $request = GeneralUtility::makeInstance(ServerRequestInterface::class);
        /** @var BackendConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(BackendConfigurationManager::class);
        $typoScriptSetup = $configurationManager->getTypoScriptSetup($request);

        return $typoScriptSetup['plugin.']['tx_' . strtolower($extensionName) . '.'] ?? [];
    }
}
