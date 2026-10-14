<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Model\Question;
use Jp\Jpfaq\Domain\Model\Questioncomment;
use Jp\Jpfaq\Domain\Repository\QuestioncommentRepository;
use Jp\Jpfaq\Domain\Repository\QuestionRepository;
use Jp\Jpfaq\Service\SendMailService;
use Jp\Jpfaq\Service\SpamProtection;
use Jp\Jpfaq\Utility\CommentIp;
use Jp\Jpfaq\Utility\TypoScript;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Attribute\RateLimit;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

class QuestioncommentController extends ActionController
{
    public function __construct(
        protected readonly QuestioncommentRepository $questioncommentRepository,
        protected readonly QuestionRepository $questionRepository,
        protected readonly SendMailService $sendMailService,
        protected readonly SpamProtection $spamProtection
    ) {}

    /**
     * Initialize
     */
    public function initializeAction(): void
    {
        // Override empty flexform settings
        $tsSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'jpfaq_faq',
            'Faq'
        );

        $originalSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
            $typoScriptUtility = GeneralUtility::makeInstance(TypoScript::class);
            $originalSettings = $typoScriptUtility->override($originalSettings, $tsSettings);
        }

        $this->settings = $originalSettings;
    }

    /**
     * Action comment
     *
     * Shows comment form
     *
     * @param Question $question
     *
     * @return ResponseInterface
     */
    public function commentAction(Question $question): ResponseInterface
    {
        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        $this->view->assignMultiple([
            'currentUid' => $currentUid,
            'question' => $question,
            'formToken' => $this->spamProtection->createFormToken(),
        ]);

        return $this->htmlResponse();
    }

    /**
     * Action addComment
     *
     * Add a question comment in db and return it to the Ajax page
     *
     * @param Question $question
     * @param Questioncomment $newQuestioncomment
     * @param int $pluginUid
     *
     * @return ResponseInterface|ForwardResponse
     */
    #[RateLimit(limit: 5, interval: '15 minutes')]
    public function addCommentAction(Question $question, Questioncomment $newQuestioncomment, int $pluginUid): ResponseInterface|ForwardResponse
    {
        $formToken = $this->request->hasArgument('formToken') ? (string)$this->request->getArgument('formToken') : '';
        // Answer spam like a request for another plugin, so a bot gets no hint
        if ($this->spamProtection->isSpam($this->request, $newQuestioncomment->getFinfo(), $formToken, $this->settings)) {
            return $this->responseFactory->createResponse();
        }

        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        if ($currentUid == $pluginUid) {
            $newQuestioncomment->setIp(CommentIp::fromRequest($this->request));

            $question->addComment($newQuestioncomment);

            try {
                $this->questionRepository->update($question);
            } catch (IllegalObjectTypeException|UnknownObjectException $e) {
            }

            // Send notification emails
            $emailSettings = $this->settings['question']['comment']['email'] ?? [];

            if ($emailSettings['enable'] ?? false) {
                $sender = [$emailSettings['sender']['email'] => $emailSettings['sender']['name']];
                $comment = '<p><i>' . nl2br(htmlspecialchars($newQuestioncomment->getComment())) . '</i></p><p><i>' . htmlspecialchars($newQuestioncomment->getName()) . '<br/>' . htmlspecialchars($newQuestioncomment->getEmail()) . '</i></p><br/>';

                $this->sendMailService->sendMail(
                    $emailSettings['receivers']['email'],
                    $sender,
                    $emailSettings['subject'],
                    $this->formatRte($emailSettings['introText']) . '<br/><strong>' . $question->getUid() . '. ' . htmlspecialchars($question->getQuestion()) . '</strong>' . $comment . $this->formatRte($emailSettings['closeText'])
                );

                if ($emailSettings['sendCommenterNotification'] ?? false) {
                    $this->sendMailService->sendMail(
                        $newQuestioncomment->getEmail(),
                        $sender,
                        $emailSettings['commenter']['subject'],
                        $this->formatRte($emailSettings['commenter']['introText']) . '<br/><strong>' . htmlspecialchars($question->getQuestion()) . '</strong>' . $comment . $this->formatRte($emailSettings['commenter']['closeText'])
                    );
                }
            }

            return new ForwardResponse('thankForComment');
        }

        // Else do not render view
        // When multiple plugins on a page we want action for the one who called it
        return $this->responseFactory
            ->createResponse();
    }

    /**
     * Action thankForComment
     *
     * @param Questioncomment $newQuestioncomment
     * @param int $pluginUid
     *
     * @return ResponseInterface
     */
    public function thankForCommentAction(Questioncomment $newQuestioncomment, int $pluginUid): ResponseInterface
    {
        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        $emailSettings = $this->settings['question']['comment']['email'] ?? [];
        $emailNotification = (int)($emailSettings['sendCommenterNotification'] ?? 0);

        // First tab of flexform
        if ((int)($emailSettings['enable'] ?? 0) === 0) {
            $emailNotification = 0;
        }

        if ($currentUid == $pluginUid) {
            $this->view->assignMultiple([
                'comment' => $newQuestioncomment,
                'emailNotification' => $emailNotification,
            ]);

            return $this->htmlResponse();
        }

        // Else do not render view
        // When multiple plugins on a page we want action for the one who called it
        // The thank you message will however appear at every plugin
        return $this->responseFactory
            ->createResponse();
    }

    /**
     * Format / clean an editor supplied string with parseFunc
     */
    private function formatRte(?string $str): string
    {
        return $this->request->getAttribute('currentContentObject')->parseFunc((string)$str, null, '< lib.parseFunc_RTE');
    }
}
