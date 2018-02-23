<?php
namespace Jp\Jpfaq\Utility;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Class ConfigurationUtility
 */
class ConfigurationUtility
{
    /**
     * Check if TYPO3 smaller then 8.7 is running
     *
     * @return bool
     */
    public static function isOlderThan8Lts()
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 8007000;
    }
}