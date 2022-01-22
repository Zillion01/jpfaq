<?php

namespace Jp\Jpfaq\Service;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SendMailService
 *
 */
class SendMailService
{
    /**
     * Send a simple email
     *
     * @param string $receivers
     * @param array $sender Email and Name from Sender
     * @param string $subject Subject line
     * @param string $body Message content
     *
     * @return bool mail was sent?
     */
    public static function sendMail(string $receivers, array $sender, string $subject, string $body)
    {
        $mail = GeneralUtility::makeInstance(MailMessage::class);

        $receivers = explode(',', str_replace(' ', '', $receivers));

        $mail->setTo($receivers);
        $mail->setFrom($sender);
        $mail->setSubject($subject);
        $mail->html('<html><head></head><body>' . $body . ' </body></html>');

        $mail->send();

        return $mail->isSent();
    }
}
