<?php

namespace Jp\Jpfaq\Controller;

/***
 *
 * This file is part of the "Test Faq" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018
 *
 ***/

use Jp\Jpfaq\Service\SendMailService;
use Jp\Jpfaq\Domain\Model\Question;
use Jp\Jpfaq\Domain\Model\Questioncomment;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * QuestioncommentController
 */
class QuestioncommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * questioncommentRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\QuestioncommentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $questioncommentRepository = null;

    /**
     * questionRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\QuestionRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $questionRepository = null;

    /**
     * Action comment
     *
     * Shows comment form
     *
     * @param Question $question
     *
     */
    public function commentAction(Question $question) {
        $currentUid = $this->getCurrentUid();

        $this->view->assignMultiple(array(
            'currentUid' => $currentUid,
            'question' => $question
        ));
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
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     *
     * @return bool
     */
    public function addCommentAction(Question $question, Questioncomment $newQuestioncomment, int $pluginUid)
    {
        // If honeypot field 'finfo' is filled by spambot do not add new comment
        if ($newQuestioncomment->getFinfo()) {
            $this->redirect('list', 'Question');
        }

        $currentUid = $this->getCurrentUid();

        if ($currentUid == $pluginUid) {
            // Set comment IP
            $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jpfaq');
            $anonymizeIpSetting = $extensionConfiguration['anonymizeIp'];
            $commentIp= (string)GeneralUtility::getIndpEnv('REMOTE_ADDR');

            if ($anonymizeIpSetting) {
                $parts = explode(".", $commentIp);
                $newQuestioncomment->setIp($parts[0] . '.' . $parts[1] . '.x.x');
            } else {
                $newQuestioncomment->setIp($commentIp);
            }

            $question->addComment($newQuestioncomment);
            $this->questionRepository->update($question);

            // SignalSlotDispatcher, connect with this to run a custom action after comment creation
            $this->signalSlotDispatcher->dispatch(__CLASS__, 'NewFaqComment', [$question, $newQuestioncomment]);

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

            $this->forward('thankForComment');
        } else {
            // Do not render view
            // When multiple plugins on a page we want action for the one who called it
            return false;
        }
    }

    /**
     * Action thankForComment
     *
     * @param Questioncomment $newQuestioncomment
     * @param int $pluginUid
     *
     * @return bool
     */
    public function thankForCommentAction(Questioncomment $newQuestioncomment, int $pluginUid)
    {
        $currentUid = $this->getCurrentUid();

        if ($currentUid == $pluginUid) {
            $this->view->assign('comment', $newQuestioncomment);
        } else {
            # Do not render view
            # When multiple plugins on a page we want action for the one who called it
            # The thank you message will however appear at every plugin
            return false;
        }
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
