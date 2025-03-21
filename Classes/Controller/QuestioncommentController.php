<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Model\Question;
use Jp\Jpfaq\Domain\Model\Questioncomment;
use Jp\Jpfaq\Domain\Repository\QuestioncommentRepository;
use Jp\Jpfaq\Domain\Repository\QuestionRepository;
use Jp\Jpfaq\Service\SendMailService;
use Jp\Jpfaq\Utility\TypoScript;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

class QuestioncommentController extends ActionController
{
    protected QuestioncommentRepository $questioncommentRepository;
    protected QuestionRepository $questionRepository;
    protected ConfigurationManagerInterface $configurationManager;

    /**
     * @param QuestioncommentRepository $questioncommentRepository
     * @param QuestionRepository $questionRepository
     * @param ConfigurationManagerInterface $configurationManager
     */
    public function __construct(
        QuestioncommentRepository $questioncommentRepository,
        QuestionRepository $questionRepository,
        ConfigurationManagerInterface $configurationManager
    ) {
        $this->questioncommentRepository = $questioncommentRepository;
        $this->questionRepository = $questionRepository;
        $this->configurationManager = $configurationManager;
    }

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
    public function addCommentAction(Question $question, Questioncomment $newQuestioncomment, int $pluginUid): ResponseInterface|ForwardResponse
    {
        // If honeypot field 'finfo' is filled by spambot do not add new comment
        if ($newQuestioncomment->getFinfo()) {
            $this->redirect('list', 'Question');
        }

        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];
        $anonymizeIpSetting = null;

        if ($currentUid == $pluginUid) {
            // Set comment IP
            try {
                $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jpfaq');

                $anonymizeIpSetting = $extensionConfiguration['anonymizeIp'];
            } catch (\Exception $exception) {
                // do nothing
            }

            $commentIp = (string)GeneralUtility::getIndpEnv('REMOTE_ADDR');

            if ($anonymizeIpSetting) {
                $parts = explode('.', $commentIp);
                $newQuestioncomment->setIp($parts[0] . '.' . $parts[1] . '.x.x');
            } else {
                $newQuestioncomment->setIp($commentIp);
            }

            $question->addComment($newQuestioncomment);

            try {
                $this->questionRepository->update($question);
            } catch (IllegalObjectTypeException|UnknownObjectException $e) {
            }

            // Send notification emails
            $emailSettings = $this->settings['question']['comment']['email'];

            if ($emailSettings['enable']) {
                $emailBodyCenterText = '<br/><strong>' . $question->getUid() . '. ' . $question->getQuestion() . '</strong>' . '<p><i>' . $this->formatRte($this->request->getAttribute('currentContentObject'), $newQuestioncomment->getComment()) . '</i></p><p><i>' . htmlspecialchars($newQuestioncomment->getName()) . '<br/>' . htmlspecialchars($newQuestioncomment->getEmail()) . '</i></p><br/>';

                $sender = [htmlspecialchars($emailSettings['sender']['email']) => htmlspecialchars($emailSettings['sender']['name'])];

                SendMailService::sendMail(
                    $receivers = htmlspecialchars($emailSettings['receivers']['email']),
                    $sender,
                    $subject = htmlspecialchars($emailSettings['subject']),
                    $body = $this->formatRte($this->request->getAttribute('currentContentObject'), $emailSettings['introText']) . $emailBodyCenterText . $this->formatRte($this->request->getAttribute('currentContentObject'), $emailSettings['closeText'])
                );

                if ($emailSettings['sendCommenterNotification']) {
                    $emailBodyCenterText = '<br/><strong>' . $question->getQuestion() . '</strong>' . '<p><i>' . $this->formatRte($this->request->getAttribute('currentContentObject'), $newQuestioncomment->getComment()) . '</i></p><p><i>' . htmlspecialchars($newQuestioncomment->getName()) . '<br/>' . htmlspecialchars($newQuestioncomment->getEmail()) . '</i></p><br/>';

                    SendMailService::sendMail(
                        $receivers = htmlspecialchars($newQuestioncomment->getEmail()),
                        $sender,
                        $subject = htmlspecialchars($emailSettings['commenter']['subject']),
                        $body = $this->formatRte($this->request->getAttribute('currentContentObject'), $emailSettings['commenter']['introText']) . $emailBodyCenterText . $this->formatRte($this->request->getAttribute('currentContentObject'), $emailSettings['commenter']['closeText'])
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

        $emailNotification = (int)$this->settings['question']['comment']['email']['sendCommenterNotification'];

        // First tab of flexform
        if ((int)$this->settings['question']['comment']['email']['enable'] == 0) {
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
     * Format / clean a string with parseFunc
     *
     * @param $request
     * @param $str
     *
     * @return string
     */
    private function formatRte($request, $str): string
    {
        return $request->parseFunc($str, [], '< lib.parseFunc_RTE');
    }
}
