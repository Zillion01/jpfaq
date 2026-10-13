<?php

declare(strict_types=1);

namespace Jp\Jpfaq\Service;

use Jp\Jpfaq\Utility\IsBot;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Exception\Crypto\InvalidHashStringException;

/**
 * Spam checks for the comment forms: honeypot, known bots and a signed form render time
 */
final readonly class SpamProtection
{
    private const HMAC_SECRET = 'jpfaq-comment-form';

    public function __construct(private HashService $hashService) {}

    public function createFormToken(): string
    {
        return $this->hashService->appendHmac((string)time(), self::HMAC_SECRET);
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function isSpam(ServerRequestInterface $request, string $honeypot, string $formToken, array $settings): bool
    {
        return $honeypot !== ''
            || IsBot::isBot($request, (string)($settings['bots'] ?? ''))
            || $this->submittedTooFast($formToken, (int)($settings['spamProtection']['minimumSecondsBeforeSubmit'] ?? 3));
    }

    private function submittedTooFast(string $formToken, int $minimumSeconds): bool
    {
        try {
            $renderedAt = (int)$this->hashService->validateAndStripHmac($formToken, self::HMAC_SECRET);
        } catch (InvalidHashStringException) {
            return true;
        }

        return time() - $renderedAt < $minimumSeconds;
    }
}
