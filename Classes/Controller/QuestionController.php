<?php
namespace Jp\Jpfaq\Controller;

/***
 * This file is part of the "jpFAQ" Extension for TYPO3 CMS.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *  (c) 2016
 ***/

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
                $this->settings['questions']['categories'][] = (int) $category;
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
        $questions = $this->questionRepository->findQuestionsWithConstraints($restrictToCategories);

        $this->view->assignMultiple(array(
            'showSearchForm' => intval($this->settings['flexform']['showSearch']),
            'showNumberOfResults' => intval($this->settings['flexform']['showNumberOfResults']),
            'questions' => $questions
        ));
    }
}
