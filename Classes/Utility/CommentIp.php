<?php

declare(strict_types=1);

namespace Jp\Jpfaq\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\IpAnonymizationUtility;

/**
 * IP address stored with a comment, anonymized when the extension setting "anonymizeIp" is enabled
 */
final class CommentIp
{
    public static function fromRequest(ServerRequestInterface $request): string
    {
        $normalizedParams = $request->getAttribute('normalizedParams');
        $ip = $normalizedParams instanceof NormalizedParams ? $normalizedParams->getRemoteAddress() : '';

        if ($ip === '' || !self::anonymizationEnabled()) {
            return $ip;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            return $parts[0] . '.' . $parts[1] . '.x.x';
        }

        return IpAnonymizationUtility::anonymizeIp($ip, 2);
    }

    private static function anonymizationEnabled(): bool
    {
        try {
            return (bool)(GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jpfaq', 'anonymizeIp') ?? false);
        } catch (\Exception) {
            return false;
        }
    }
}
