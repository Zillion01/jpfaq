<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Model\Question;
use Jp\Jpfaq\Domain\Repository\CategoryRepository;
use Jp\Jpfaq\Domain\Repository\QuestionRepository;
use Jp\Jpfaq\Utility\IsBot;
use Jp\Jpfaq\Utility\TypoScript;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

/**
 * QuestionController
 */
class QuestionController extends ActionController
{
    protected QuestionRepository $questionRepository;

    protected CategoryRepository $categoryRepository;

    /**
     * @param QuestionRepository $questionRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        QuestionRepository $questionRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->questionRepository = $questionRepository;
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

        // Avoid code injection
        $this->settings['questions']['categories'] = [];
        if ($this->settings['flexform']['selectCategory']) {
            $categories = explode(',', $this->settings['flexform']['selectCategory']);
            foreach ($categories as $category) {
                $this->settings['questions']['categories'][] = (int)$category;
            }
        }
    }

    /**
     * action list
     *
     * @return ResponseInterface
     * @throws InvalidQueryException
     */
    public function listAction(): ResponseInterface
    {
        $restrictToCategories = (array)$this->settings['questions']['categories'];
        $excludeAlreadyDisplayedQuestions = (int)($this->settings['excludeAlreadyDisplayedQuestions'] ?? 1);
        $questions = $this->questionRepository->findQuestionsWithConstraints(
            $restrictToCategories,
            $excludeAlreadyDisplayedQuestions
        );

        $categories = [];
        foreach ($restrictToCategories as $uid) {
            $categories[] = $this->categoryRepository->findByUid($uid);
        }

        if (!$restrictToCategories) {
            $restrictToCategories = ['no categories'];
        }

        $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

        $this->view->assignMultiple([
            'showSearchForm' => (int)$this->settings['flexform']['showSearch'],
            'showNumberOfResults' => (int)$this->settings['flexform']['showNumberOfResults'],
            'showQuestionCommentForm' => (int)$this->settings['flexform']['showQuestionCommentForm'],
            'showCategoriesCommentForm' => (int)$this->settings['flexform']['showCategoriesCommentForm'],
            'categories' => $categories,
            'restrictToCategories' => $restrictToCategories,
            'currentUid' => $currentUid,
            'gtag' => $this->settings['gtag'] ?? '',
            'questions' => $questions
        ]);

        return $this->htmlResponse();
    }

    /**
     * Action helpfulness
     *
     * @param Question $question
     * @param bool $helpful
     * @param int $pluginUid
     *
     * @return ResponseInterface|ForwardResponse
     *
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function helpfulnessAction(Question $question, bool $helpful, int $pluginUid): ResponseInterface|ForwardResponse
    {
        if (!IsBot::isBot()) {
            $currentUid = $this->request->getAttribute('currentContentObject')->data['uid'];

            if ($currentUid == $pluginUid) {
                $this->updateHelpful($question, $helpful);

                if (!$helpful) {
                    // Show comment form
                    return (new ForwardResponse('comment'))
                        ->withControllerName('Questioncomment');
                }

                // Show helpfullness template
                return $this->htmlResponse();
            }
        }

        // Else do not render view
        // When multiple plugins on a page we want action for the one who called it
        return $this->responseFactory
            ->createResponse();
    }

    /**
     * Update helpful or nothelpful of a question in db and session
     *
     * @param Question $question
     * @param bool $helpful
     *
     *
     * @throws UnknownObjectException
     * @throws IllegalObjectTypeException
     */
    private function updateHelpful(Question $question, bool $helpful): void
    {
        $questionUid = $question->getUid();
        $questionHelpful = $question->getHelpful();
        $questionNotHelpful = $question->getNothelpful();
        $userClickedHelpfulness = $GLOBALS['TSFE']->fe_user->getKey('ses', $questionUid);

        // User already clicked helpful for this question this session
        if (isset($userClickedHelpfulness)) {
            // User changes helpful to nothelpful
            if ($userClickedHelpfulness & !$helpful) {
                $question->setNothelpful($questionNotHelpful + 1);
                if ($questionHelpful > 0) {
                    $question->setHelpful($questionHelpful - 1);
                }
            } elseif (!$userClickedHelpfulness & $helpful) {
                // User changes nothelpful to helpful
                $question->setHelpful($questionHelpful + 1);
                if ($questionNotHelpful > 0) {
                    $question->setNothelpful($questionNotHelpful - 1);
                }
            }
        } else {
            // User has not clicked helpful or nothelpful for this question this session
            if ($helpful) {
                $question->setHelpful($questionHelpful + 1);
            } else {
                $question->setNothelpful($questionNotHelpful + 1);
            }
        }

        $this->questionRepository->update($question);

        // Store user interaction on helpfulness in session
        $GLOBALS['TSFE']->fe_user->setKey('ses', $questionUid, $helpful);
    }
}
