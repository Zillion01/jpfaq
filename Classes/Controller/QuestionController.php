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
     * @param Question|null $question
     * @param string $selectCategory
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listAction(Question $question = null, string $selectCategory = '')
    {

        if (!is_null($question)) {
            $this->forward('detail', null, null, ['question' => $question]);
        }

        $restrictToCategories = ($selectCategory) ? [0 => intval($selectCategory)] : $this->settings['questions']['categories'];
        $excludeAlreadyDisplayedQuestions = intval($this->settings['excludeAlreadyDisplayedQuestions']);
        $questions = $this->questionRepository->findQuestionsWithConstraints($restrictToCategories, $excludeAlreadyDisplayedQuestions);

        $categories = [];
        foreach ($restrictToCategories as $uid) {
            $categories[] = $this->categoryRepository->findByUid($uid);
        }

        if (!$restrictToCategories) {
            $restrictToCategories = ['no categories'];
        }

        $currentUid = $this->getCurrentUid();

        $this->view->assignMultiple(array(
            'showSearchForm' => intval($this->settings['flexform']['showSearch']),
            'showQuestionCommentForm' => intval($this->settings['flexform']['showQuestionCommentForm']),
            'showCategoriesCommentForm' => intval($this->settings['flexform']['showCategoriesCommentForm']),
            'categories' => $categories,
            'restrictToCategories' => $restrictToCategories,
            'currentUid' => $currentUid,
            'gtag' => $this->settings['gtag'],
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
            $restrictToCategories = ['no categories'];
        }

        $this->view->assignMultiple(array(
            'question' => $question,
            'showQuestionCommentForm' => intval($this->settings['flexform']['showQuestionCommentForm']),
            'currentUid' => $currentUid,
            'gtag' => $gtag,
            'showCategoriesCommentForm' => intval($this->settings['flexform']['showCategoriesCommentForm']),
            'restrictToCategories' => $restrictToCategories,
            'categories' => $categories
        ));
    }

    /**
     * action categoryDetail
     *
     * @param string $selectCategory
     * @return void
     */

    public function categoryDetailAction(string $selectCategory){
        $this->forward('list', null, null, ['selectCategory' => $selectCategory]);
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
