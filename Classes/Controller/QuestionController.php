<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Model\Question;
use Jp\Jpfaq\Domain\Repository\CategoryRepository;
use Jp\Jpfaq\Domain\Repository\QuestionRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * QuestionController
 */
class QuestionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var QuestionRepository
     */
    protected $questionRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;


    /**
     * @param QuestionRepository $questionRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        QuestionRepository $questionRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initializeListAction()
    {
        # Avoid code injection
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
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     *
     */
    public function listAction(): ResponseInterface
    {
        $restrictToCategories = $this->settings['questions']['categories'];
        $excludeAlreadyDisplayedQuestions = (int)$this->settings['excludeAlreadyDisplayedQuestions'];
        $questions = $this->questionRepository->findQuestionsWithConstraints($restrictToCategories,
            $excludeAlreadyDisplayedQuestions);

        $categories = [];
        foreach ($restrictToCategories as $uid) {
            $categories[] = $this->categoryRepository->findByUid($uid);
        }

        if (!$restrictToCategories) {
            $restrictToCategories = ['no categories'];
        }

        $currentUid = $this->getCurrentUid();

        $this->view->assignMultiple([
            'showSearchForm' => (int)$this->settings['flexform']['showSearch'],
            'showNumberOfResults' => (int)$this->settings['flexform']['showNumberOfResults'],
            'showQuestionCommentForm' => (int)$this->settings['flexform']['showQuestionCommentForm'],
            'showCategoriesCommentForm' => (int)$this->settings['flexform']['showCategoriesCommentForm'],
            'categories' => $categories,
            'restrictToCategories' => $restrictToCategories,
            'currentUid' => $currentUid,
            'gtag' => $this->settings['gtag'],
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
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function helpfulnessAction(Question $question, bool $helpful, int $pluginUid)
    {
        $currentUid = $this->getCurrentUid();

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

        // Else do not render view
        // When multiple plugins on a page we want action for the one who called it
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

    /**
     * Update helpful or nothelpful of a question in db and session
     *
     * @param Question $question
     * @param bool $helpful
     *
     * @return void
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    private function updateHelpful(Question $question, bool $helpful)
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
