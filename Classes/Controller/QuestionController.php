<?php

namespace Jp\Jpfaq\Controller;

/***
 * This file is part of the "jpFAQ" Extension for TYPO3 CMS.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *  (c) 2016
 ***/

use Jp\Jpfaq\Domain\Model\Question;

/**
 * QuestionController
 */
class QuestionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * questionRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\QuestionRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $questionRepository = null;

    /**
     * categoryRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\CategoryRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $categoryRepository = null;

    /**
     * Initialize basic variables
     *
     * @return void
     */
    public function initializeListAction()
    {
        # Avoid code injection
        $this->settings['questions']['categories'] = [];
        if ($this->settings['flexform']['selectedCategory']) {
            $categories = explode(',', $this->settings['flexform']['selectedCategory']);
            foreach ($categories as $category) {
                $this->settings['questions']['categories'][] = (int)$category;
            }
        }
    }

    /**
     * action list
     *
     * @param Question|null $question
     * @param string $selectedCategory
     * @param int $categoryDetail
     * @param string $singleViewPid
     * @param array $gtag
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listAction(Question $question = null, string $selectedCategory = '', int $categoryDetail = 0, string $singleViewPid = '0', array $gtag = [])
    {

        if ($question !== null) {
            $this->forward('detail', null, null, ['question' => $question]);
        }

        if ($this->settings['singleViewPid']) {
            $singleViewPid = $this->settings['singleViewPid'];
        }

        if ($this->settings['gtag']) {
            $gtag = $this->settings['gtag'];
        }

        $restrictToCategories = ($selectedCategory) ? [(int)$selectedCategory] : $this->settings['questions']['categories'];
        $excludeAlreadyDisplayedQuestions = (int)$this->settings['excludeAlreadyDisplayedQuestions'];
        $questions = $this->questionRepository->findQuestionsWithConstraints($restrictToCategories, $excludeAlreadyDisplayedQuestions);

        $categories = [];
        foreach ($restrictToCategories as $uid) {
            $categories[] = $this->categoryRepository->findByUid($uid);
        }

        if (!$restrictToCategories) {
            $restrictToCategories = [];
        }

        $currentUid = $this->getCurrentUid();

        $this->view->assignMultiple(array(
            'showSearchForm' => (int)$this->settings['flexform']['showSearch'],
            'showNumberOfResults' => (int)$this->settings['flexform']['showNumberOfResults'],
            'showQuestionCommentForm' => (int)$this->settings['flexform']['showQuestionCommentForm'],
            'showCategoriesCommentForm' => (int)$this->settings['flexform']['showCategoriesCommentForm'],
            'categories' => $categories,
            'restrictToCategories' => $restrictToCategories,
            'currentUid' => $currentUid,
            'categoryDetail' => $categoryDetail,
            'singleViewPid' => (int)$singleViewPid,
            'gtag' => $gtag,
            'questions' => $questions
        ));
    }

    /**
     * action detail
     *
     * @param Question $question
     * @param array $gtag
     *
     * @return void
     */
    public function detailAction(Question $question, array $gtag)
    {
        $currentUid = $this->getCurrentUid();

        $restrictToCategories = $this->settings['questions']['categories'];

        $categories = [];
        foreach ($restrictToCategories as $uid) {
            $categories[] = $this->categoryRepository->findByUid($uid);
        }

        if (!$restrictToCategories) {
            $restrictToCategories = [''];
        }

        $this->view->assignMultiple(array(
            'question' => $question,
            'showQuestionCommentForm' => (int)$this->settings['flexform']['showQuestionCommentForm'],
            'currentUid' => $currentUid,
            'gtag' => $gtag,
            'showCategoriesCommentForm' => (int)$this->settings['flexform']['showCategoriesCommentForm'],
            'restrictToCategories' => $restrictToCategories,
            'categories' => $categories
        ));
    }

    /**
     * action categoryDetail
     *
     * @param string $selectedCategory
     * @param array $gtag
     * @param int $categoryDetail
     * @param string $singleViewPid
     * @return void
     */
    public function categoryDetailAction(string $selectedCategory, array $gtag, int $categoryDetail = 0, string $singleViewPid = '0'){
        $this->forward('list', null, null,
            ['selectedCategory' => $selectedCategory, 'categoryDetail' => $categoryDetail, 'singleViewPid' => $singleViewPid, 'gtag' => $gtag]);
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
