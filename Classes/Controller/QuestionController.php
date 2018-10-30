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
     * @inject
     */
    protected $questionRepository = null;

    /**
     * categoryRepository
     *
     * @var \Jp\Jpfaq\Domain\Repository\CategoryRepository
     * @inject
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
     * @return void
     */
    public function listAction()
    {
        $restrictToCategories = $this->settings['questions']['categories'];
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
            'showNumberOfResults' => intval($this->settings['flexform']['showNumberOfResults']),
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
     * Action helpfulness
     *
     * @param Question $question
     * @param bool $helpful
     * @param int $pluginUid
     *
     * @return bool
     */
    public function helpfulnessAction(Question $question, bool $helpful, int $pluginUid)
    {
        $currentUid = $this->getCurrentUid();

        if ($currentUid == $pluginUid) {
            $this->updateHelpful($question, $helpful);

            if (!$helpful) {
                // Show comment form
                $this->forward('comment', 'Questioncomment', null);
            }
        } else {
            # Do not render helpfulness view
            # When multiple plugins on a page we want action for the one who called it
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

    /**
     * Update helpful or nothelpful of a question in db and session
     *
     * @param Question $question
     * @param bool $helpful
     *
     * @return void
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
