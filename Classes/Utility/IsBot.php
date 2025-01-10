<?php

namespace Jp\Jpfaq\Utility;

class IsBot
{
    /**
     * Check if browser is a well known bot
     *
     * @return bool
     */
    public static function isBot(): bool
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $bots = Typo3Utility::getSettings('tx_jpfaq_faq')['bots'];
        $bots = explode(',', $bots);

        foreach ($bots as $bot) {
            if (stripos($userAgent, htmlspecialchars($bot)) !== false) return true;
        }

        return false;
    }
}
