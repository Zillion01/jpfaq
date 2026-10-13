<?php

namespace Jp\Jpfaq\Utility;

use Psr\Http\Message\ServerRequestInterface;

class IsBot
{
    /**
     * Check if the user agent of the request contains one of the comma separated bot names
     */
    public static function isBot(ServerRequestInterface $request, string $bots): bool
    {
        $userAgent = $request->getHeaderLine('User-Agent');

        foreach (explode(',', $bots) as $bot) {
            if (trim($bot) === '') {
                continue;
            }
            if (stripos($userAgent, htmlspecialchars($bot)) !== false) {
                return true;
            }
        }

        return false;
    }
}
