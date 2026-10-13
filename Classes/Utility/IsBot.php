<?php

namespace Jp\Jpfaq\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IsBot
{
    /**
     * Check if the user agent of the request contains one of the comma separated bot names
     */
    public static function isBot(ServerRequestInterface $request, string $bots): bool
    {
        $userAgent = $request->getHeaderLine('User-Agent');

        foreach (GeneralUtility::trimExplode(',', $bots, true) as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
