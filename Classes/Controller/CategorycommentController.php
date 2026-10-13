<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Model\Category;
use Jp\Jpfaq\Domain\Model\Categorycomment;
use Jp\Jpfaq\Domain\Repository\CategorycommentRepository;
use Jp\Jpfaq\Domain\Repository\CategoryRepository;
use Jp\Jpfaq\Service\SendMailService;
use Jp\Jpfaq\Utility\CommentIp;
use Jp\Jpfaq\Utility\IsBot;
use Jp\Jpfaq\Utility\TypoScript;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

class CategorycommentController extends ActionController
{
    public function __construct(
        protected readonly CategorycommentRepository $categorycommentRepository,
        protected readonly CategoryRepository $categoryRepository,
        protected readonly SendMailService $sendMailService
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
     * @param array<int|string> $catUids
     * @param int $pluginUid
     *
     * @return ResponseInterface
     */
    public function commentAction(array $catUids, int $pluginUid): ResponseInterface
    {
        if (!IsBot::isBot($this->request, (string)($this->settings['bots'] ?? ''))) {
            $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

            if ($currentUid == $pluginUid) {
                $this->view->assignMultiple([
                    'catUids' => $catUids,
                    'currentUid' => $currentUid,
                ]);

                return $this->htmlResponse();
            }
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
     * @param array<int|string> $catUids
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
            return $this->responseFactory->createResponse();
        }

        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        $categories = [];
        $categoryNames = '';
        if ($catUids[0] !== 'no categories') {
            foreach ($catUids as $catUid) {
                $catUid = (int)$catUid;

                $tempCat = $this->categoryRepository->findByUid($catUid);
                if ($tempCat instanceof Category) {
                    $categories[] = $tempCat;
                    $categoryNames = $categoryNames . $tempCat->getCategory() . '<br/>';
                }
            }
        }

        if ($currentUid == $pluginUid) {
            $newCategorycomment->setIp(CommentIp::fromRequest($this->request));

            foreach ($categories as $category) {
                $newCategorycomment->addCategory($category);
            }

            $this->categorycommentRepository->add($newCategorycomment);

            // Send notification emails
            $emailSettings = $this->settings['category']['comment']['email'] ?? [];

            if ($emailSettings['enable'] ?? false) {
                $sender = [htmlspecialchars($emailSettings['sender']['email']) => htmlspecialchars($emailSettings['sender']['name'])];

                $emailBodyCenterText = '<br/><strong>' . $categoryNames . '</strong>' . '<p><i>' . $this->formatRte($newCategorycomment->getComment()) . '</i></p><p><i>' . htmlspecialchars($newCategorycomment->getName()) . '<br/>' . htmlspecialchars($newCategorycomment->getEmail()) . '</i></p><br/>';

                $this->sendMailService->sendMail(
                    htmlspecialchars($emailSettings['receivers']['email']),
                    $sender,
                    $emailSettings['subject'],
                    $this->formatRte($emailSettings['introText']) . $emailBodyCenterText . $this->formatRte($emailSettings['closeText'])
                );

                if ($emailSettings['sendCommenterNotification'] ?? false) {
                    $emailBodyCenterText = '<br/>' . '<p><i>' . $this->formatRte($newCategorycomment->getComment()) . '</i></p><p><i>' . htmlspecialchars($newCategorycomment->getName()) . '<br/>' . htmlspecialchars($newCategorycomment->getEmail()) . '</i></p><br/>';

                    $this->sendMailService->sendMail(
                        htmlspecialchars($newCategorycomment->getEmail()),
                        $sender,
                        htmlspecialchars($emailSettings['commenter']['subject']),
                        $this->formatRte($emailSettings['commenter']['introText']) . $emailBodyCenterText . $this->formatRte($emailSettings['commenter']['closeText'])
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

        $emailSettings = $this->settings['category']['comment']['email'] ?? [];
        $emailNotification = (int)($emailSettings['sendCommenterNotification'] ?? 0);

        // First tab of flexform
        if ((int)($emailSettings['enable'] ?? 0) === 0) {
            $emailNotification = 0;
        }

        if ($currentUid == $pluginUid) {
            $this->view->assignMultiple([
                'comment' => $newCategorycomment,
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
     */
    private function formatRte(?string $str): string
    {
        return $this->request->getAttribute('currentContentObject')->parseFunc((string)$str, null, '< lib.parseFunc_RTE');
    }
}
