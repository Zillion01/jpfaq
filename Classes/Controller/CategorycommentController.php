<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Model\Categorycomment;
use Jp\Jpfaq\Domain\Repository\CategorycommentRepository;
use Jp\Jpfaq\Domain\Repository\CategoryRepository;
use Jp\Jpfaq\Service\SendMailService;
use Jp\Jpfaq\Utility\TypoScript;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

class CategorycommentController extends ActionController
{
    protected CategorycommentRepository $categorycommentRepository;

    protected CategoryRepository $categoryRepository;

    /**
     * @param CategorycommentRepository $categorycommentRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategorycommentRepository $categorycommentRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->categorycommentRepository = $categorycommentRepository;
        $this->categoryRepository = $categoryRepository;
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
     * @param array $catUids
     * @param int $pluginUid
     *
     * @return ResponseInterface
     */
    public function commentAction(array $catUids, int $pluginUid): ResponseInterface
    {
        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        if ($currentUid == $pluginUid) {
            $this->view->assignMultiple([
                'catUids' => $catUids,
                'currentUid' => $currentUid,
            ]);

            return $this->htmlResponse();
        }

        // Else do not render view
        // When multiple plugins on a page we want action for the one who called it
        return $this->responseFactory
            ->createResponse();
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
     * @return ResponseInterface
     *
     * @throws IllegalObjectTypeException
     */
    public function addCommentAction(
        Categorycomment $newCategorycomment,
        array $catUids,
        int $pluginUid
    ): ResponseInterface {
        // If honeypot field 'finfo' is filled by spambot do not add new comment
        if ($newCategorycomment->getFinfo()) {
            $this->redirect('list', 'Question');
        }

        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        $categories = [];
        $categoryNames = '';
        if ($catUids[0] !== 'no categories') {
            foreach ($catUids as $catUid) {
                $catUid = (int)$catUid;

                $tempCat = $this->categoryRepository->findByUid($catUid);
                $categories[] = $tempCat;
                $categoryNames = $categoryNames . $tempCat->getCategory() . '<br/>';
            }
        }

        if ($currentUid == $pluginUid) {
            $extensionConfiguration = [];
            // Set comment IP
            try {
                $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jpfaq');
            } catch (\Exception $exception) {
                // do nothing
            }
            $anonymizeIpSetting = $extensionConfiguration['anonymizeIp'];
            $commentIp = (string)GeneralUtility::getIndpEnv('REMOTE_ADDR');

            if ($anonymizeIpSetting) {
                $parts = explode('.', $commentIp);
                $newCategorycomment->setIp($parts[0] . '.' . $parts[1] . '.x.x');
            } else {
                $newCategorycomment->setIp($commentIp);
            }

            foreach ($categories as $category) {
                $newCategorycomment->addCategory($category);
            }

            $this->categorycommentRepository->add($newCategorycomment);

            // Send notification emails
            $emailSettings = $this->settings['category']['comment']['email'];

            if ($emailSettings['enable']) {
                $sender = [htmlspecialchars($emailSettings['sender']['email']) => htmlspecialchars($emailSettings['sender']['name'])];

                $emailBodyCenterText = '<br/><strong>' . $categoryNames . '</strong>' . '<p><i>' . $this->formatRte($this->request->getAttribute('currentContentObject'), $newCategorycomment->getComment()) . '</i></p><p><i>' . htmlspecialchars($newCategorycomment->getName()) . '<br/>' . htmlspecialchars($newCategorycomment->getEmail()) . '</i></p><br/>';

                SendMailService::sendMail(
                    $receivers = htmlspecialchars($emailSettings['receivers']['email']),
                    $sender,
                    $subject = $emailSettings['subject'],
                    $body = $this->formatRte($this->request->getAttribute('currentContentObject'), $emailSettings['introText']) . $emailBodyCenterText . $this->formatRte($this->request->getAttribute('currentContentObject'), $emailSettings['closeText'])
                );

                if ($emailSettings['sendCommenterNotification']) {
                    $emailBodyCenterText = '<br/>' . '<p><i>' . $this->formatRte($this->request->getAttribute('currentContentObject'), $newCategorycomment->getComment()) . '</i></p><p><i>' . htmlspecialchars($newCategorycomment->getName()) . '<br/>' . htmlspecialchars($newCategorycomment->getEmail()) . '</i></p><br/>';

                    SendMailService::sendMail(
                        $receivers = htmlspecialchars($newCategorycomment->getEmail()),
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
     * @param Categorycomment $newCategorycomment
     * @param int $pluginUid
     *
     * @return ResponseInterface
     */
    public function thankForCommentAction(Categorycomment $newCategorycomment, int $pluginUid): ResponseInterface
    {
        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        $emailNotification = (int)$this->settings['category']['comment']['email']['sendCommenterNotification'];

        // First tab of flexform
        if ((int)$this->settings['category']['comment']['email']['enable'] == 0) {
            $emailNotification = 0;
        }

        if ($currentUid == $pluginUid) {
            $this->view->assignMultiple([
                'comment' => $newCategorycomment,
                'emailNotification' => $emailNotification
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
