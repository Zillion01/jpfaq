<?php

declare(strict_types=1);

namespace Jp\Jpfaq\Service;

use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SendMailService
 */
final readonly class SendMailService
{
    public function __construct(private MailerInterface $mailer) {}

    /**
     * Send a simple HTML email
     *
     * @param string $receivers Comma separated email addresses
     * @param array<string, string> $sender Email and Name from Sender
     * @param string $subject Subject line
     * @param string $body Message content
     */
    public function sendMail(string $receivers, array $sender, string $subject, string $body): void
    {
        $mail = GeneralUtility::makeInstance(MailMessage::class);

        $mail->setTo(GeneralUtility::trimExplode(',', $receivers, true));
        $mail->setFrom($sender);
        $mail->setSubject($subject);
        $mail->html('<html><head></head><body>' . $body . ' </body></html>');

        $this->mailer->send($mail);
    }
}
