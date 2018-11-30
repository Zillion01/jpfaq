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
use Jp\Jpfaq\Domain\Model\Categorycomment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CategorycommentController
 */
class CategorycommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * categorycommentRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\CategorycommentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $categorycommentRepository = null;

    /**
     * categoryRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\CategoryRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $categoryRepository = null;

    /**
     * Action comment
     *
     * Shows comment form
     *
     * @param array $catUids
     * @param int $pluginUid
     *
     * @return bool
     */
    public function commentAction(array $catUids, int $pluginUid)
    {
        $currentUid = $this->getCurrentUid();

        if ($currentUid == $pluginUid) {
            $this->view->assignMultiple(array(
                'catUids' => $catUids,
                'currentUid' => $currentUid,
            ));
        } else {
            # Do not render view
            # When multiple plugins on a page we want action for the one who called it
            return false;
        }
    }

    /**
     * Action addComment
     *
     * Add to database and sendmail, then show thank you
     *
     * @param Categorycomment $newCategorycomment
     * @param array $catUids
     * @param int $pluginUid
     *
     * @return bool
     */
    public function addCommentAction(Categorycomment $newCategorycomment, array $catUids, int $pluginUid)
    {
        // If honeypot field 'finfo' is filled by spambot do not add new comment
        if ($newCategorycomment->getFinfo()) {
            $this->redirect('list', 'Question');
        }

        $currentUid = $this->getCurrentUid();

        $categories = [];
        $categoryNames = '';
        if ($catUids[0] !== 'no categories') {
            foreach ($catUids as $catUid) {
                $catUid = intval($catUid);

                $tempCat = $this->categoryRepository->findByUid($catUid);
                $categories[] = $tempCat;
                $categoryNames = $categoryNames . $tempCat->getCategory() . '<br/>';
            }
        }

        if ($currentUid == $pluginUid) {
            // Set comment IP
            $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['jpfaq']);
            $anonymizeIpSetting = $extensionConfiguration['anonymizeIp'];
            $commentIp= (string)GeneralUtility::getIndpEnv('REMOTE_ADDR');

            if ($anonymizeIpSetting) {
                $parts = explode(".", $commentIp);
                $newCategorycomment->setIp($parts[0] . '.' . $parts[1] . '.x.x');
            } else {
                $newCategorycomment->setIp($commentIp);
            }

            foreach ($categories as $category) {
                $newCategorycomment->addCategory($category);
            }

            $this->categorycommentRepository->add($newCategorycomment);

            // SignalSlotDispatcher, connect with this to run a custom action after comment creation
            $this->signalSlotDispatcher->dispatch(__CLASS__, 'NewCategoriesComment', [$categories, $newCategorycomment]);

            // Send a simple email
            // To do implement with ext:form
            $emailSettings = $this->settings['category']['comment']['email'];

            if ($emailSettings['enable']) {
                $emailBodyCenterText = '<br/><strong>' . $categoryNames . '</strong>' . '<p><i>' . nl2br($newCategorycomment->getComment()) . '</i></p><p><i>' . $newCategorycomment->getName() . '<br/>' . $newCategorycomment->getEmail() . '</i></p><br/>';

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
     * @param Categorycomment $newCategorycomment
     * @param int $pluginUid
     *
     * @return bool
     */
    public function thankForCommentAction(Categorycomment $newCategorycomment, int $pluginUid)
    {
        $currentUid = $this->getCurrentUid();

        if ($currentUid == $pluginUid) {
            $this->view->assign('comment', $newCategorycomment);
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
