<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Repository\QuestioncommentRepository;
use Jp\Jpfaq\Domain\Repository\QuestionRepository;
use Jp\Jpfaq\Service\SendMailService;
use Jp\Jpfaq\Domain\Model\Question;
use Jp\Jpfaq\Domain\Model\Questioncomment;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

class QuestioncommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var QuestioncommentRepository
     */
    protected $questioncommentRepository;

    /**
     * @var QuestionRepository
     */
    protected $questionRepository;

    /**
     * @param QuestioncommentRepository $questioncommentRepository
     * @param QuestionRepository $questionRepository
     */
    public function __construct(
        QuestioncommentRepository $questioncommentRepository,
        QuestionRepository $questionRepository
    ) {
        $this->questioncommentRepository = $questioncommentRepository;
        $this->questionRepository = $questionRepository;
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
        $currentUid = $this->getCurrentUid();

        $this->view->assignMultiple([
            'currentUid' => $currentUid,
            'question' => $question
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
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     *
     */
    public function addCommentAction(Question $question, Questioncomment $newQuestioncomment, int $pluginUid)
    {
        // If honeypot field 'finfo' is filled by spambot do not add new comment
        if ($newQuestioncomment->getFinfo()) {
            $this->redirect('list', 'Question');
        }

        $currentUid = $this->getCurrentUid();
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
                $parts = explode(".", $commentIp);
                $newQuestioncomment->setIp($parts[0] . '.' . $parts[1] . '.x.x');
            } else {
                $newQuestioncomment->setIp($commentIp);
            }

            $question->addComment($newQuestioncomment);

            try {
                $this->questionRepository->update($question);
            } catch (IllegalObjectTypeException $e) {
            } catch (UnknownObjectException $e) {
            }

            // SignalSlotDispatcher, connect with this to run a custom action after comment creation
            try {
                $this->signalSlotDispatcher->dispatch(__CLASS__, 'NewFaqComment', [$question, $newQuestioncomment]);
            } catch (\Exception $exception) {
                // do nothing
            }

            // Send a simple email
            // To do implement with ext:form
            $emailSettings = $this->settings['question']['comment']['email'];
            if ($emailSettings['enable']) {

                $emailBodyCenterText = '<br/><strong>' . $question->getUid() . '. ' . $question->getQuestion() . '</strong>' . '<p><i>' . nl2br($newQuestioncomment->getComment()) . '</i></p><p><i>' . $newQuestioncomment->getName() . '<br/>' . $newQuestioncomment->getEmail() . '</i></p><br/>';

                SendMailService::sendMail(
                    $receivers = $emailSettings['receivers']['email'],
                    $sender = [$emailSettings['sender']['email'] => $emailSettings['sender']['name']],
                    $subject = $emailSettings['subject'],
                    $body = $emailSettings['introText'] . $emailBodyCenterText . $emailSettings['closeText']
                );
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
    public function thankForCommentAction(Questioncomment $newQuestioncomment, int $pluginUid)
    {
        $currentUid = $this->getCurrentUid();

        if ($currentUid == $pluginUid) {
            $this->view->assign('comment', $newQuestioncomment);

            return $this->htmlResponse();
        }

        # Else do not render view
        # When multiple plugins on a page we want action for the one who called it
        # The thank you message will however appear at every plugin
        return $this->responseFactory
            ->createResponse();
    }

    /**
     * Get current uid of content element
     *
     * @return int
     */
    private function getCurrentUid()
    {
        $cObj = $this->configurationManager->getContentObject();
        $currentUid = $cObj->data['uid'];

        return $currentUid;
    }
}
