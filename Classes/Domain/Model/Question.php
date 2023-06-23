<?php

namespace Jp\Jpfaq\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Question
 */
class Question extends AbstractEntity
{
    /**
     * question
     *
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     *
     * @var string
     */
    protected $question = '';

    /**
     * answer
     *
     * @var string
     */
    protected $answer = '';

    /**
     * helpful
     *
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     *
     * @var int
     */
    protected $helpful = '';

    /**
     * nothelpful
     *
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     *
     * @var int
     */
    protected $nothelpful = '';

    /**
     * Additional tt_content for Answer
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\TtContent>
     */
    protected $additionalContentAnswer;

    /**
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Category>
     */
    protected $categories;

    /**
     * comments
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Questioncomment>
     */
    protected $questioncomment;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     */
    protected function initStorageObjects()
    {
        $this->categories = new ObjectStorage();
        $this->additionalContentAnswer = new ObjectStorage();
        $this->questioncomment = new ObjectStorage();
    }

    /**
     * Returns the question
     *
     * @return string $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question
     *
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Returns the answer
     *
     * @return string $answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Sets the answer
     *
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Get content elements (additionalContentAnswer)
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\TtContent> $additionalContentAnswer
     */
    public function getAdditionalContentAnswer()
    {
        return $this->additionalContentAnswer;
    }

    /**
     * Get id list of content elements (additionalContentAnswer)
     *
     * @return string
     */
    public function getContentElementIdList()
    {
        $idList = [];
        $contentElements = $this->getAdditionalContentAnswer();
        if ($contentElements) {
            foreach ($this->getAdditionalContentAnswer() as $contentElement) {
                $idList[] = $contentElement->getUid();
            }
        }
        return implode(',', $idList);
    }

    /**
     * Adds a Category
     *
     * @param \Jp\Jpfaq\Domain\Model\Category $category
     */
    public function addCategory(Category $category)
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \Jp\Jpfaq\Domain\Model\Category $categoryToRemove The Category to be removed
     */
    public function removeCategory(Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Category> $categories
     */
    public function setCategories(ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the Helpful
     *
     * @return int
     */
    public function getHelpful()
    {
        return $this->helpful;
    }

    /**
     * Sets the Helpful
     *
     * @param int $helpful
     */
    public function setHelpful($helpful)
    {
        $this->helpful = $helpful;
    }

    /**
     * Returns the Nothelpful
     *
     * @return int
     */
    public function getNothelpful()
    {
        return $this->nothelpful;
    }

    /**
     * Sets the Nothelpful
     *
     * @param int $nothelpful
     */
    public function setNothelpful($nothelpful)
    {
        $this->nothelpful = $nothelpful;
    }

    /**
     * Returns the Questioncomment
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Questioncomment>
     */
    public function getQuestioncomment()
    {
        return $this->questioncomment;
    }

    /**
     * Adds a questioncomment
     *
     * @param \Jp\Jpfaq\Domain\Model\Questioncomment $questioncomment
     */
    public function addComment(Questioncomment $questioncomment)
    {
        $this->questioncomment->attach($questioncomment);
    }
}
