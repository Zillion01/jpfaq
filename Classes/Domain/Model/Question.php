<?php

namespace Jp\Jpfaq\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
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
     *
     * @var string
     */
    #[Validate(['validator' => 'NotEmpty'])]
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
     *
     * @var int
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected $helpful = 0;

    /**
     * nothelpful
     *
     *
     * @var int
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected $nothelpful = 0;

    /**
     * Additional tt_content for Answer
     *
     *
     * @var ObjectStorage<TtContent>
     */
    #[Cascade(['value' => 'remove'])]
    #[Lazy]
    protected $additionalContentAnswer;

    /**
     * categories
     *
     * @var ObjectStorage<Category>
     */
    protected $categories;

    /**
     * comments
     *
     *
     * @var ObjectStorage<Questioncomment>
     */
    #[Cascade(['value' => 'remove'])]
    #[Lazy]
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
    public function setQuestion($question): void
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
    public function setAnswer($answer): void
    {
        $this->answer = $answer;
    }

    /**
     * Get content elements (additionalContentAnswer)
     *
     * @return ObjectStorage<TtContent> $additionalContentAnswer
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

        foreach ($this->getAdditionalContentAnswer() as $contentElement) {
            $idList[] = $contentElement->getUid();
        }

        return implode(',', $idList);
    }

    /**
     * Adds a Category
     *
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param Category $categoryToRemove The Category to be removed
     */
    public function removeCategory(Category $categoryToRemove): void
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return ObjectStorage<Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
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
    public function setHelpful($helpful): void
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
    public function setNothelpful($nothelpful): void
    {
        $this->nothelpful = $nothelpful;
    }

    /**
     * Returns the Questioncomment
     *
     * @return ObjectStorage<Questioncomment>
     */
    public function getQuestioncomment()
    {
        return $this->questioncomment;
    }

    /**
     * Adds a questioncomment
     *
     * @param Questioncomment $questioncomment
     */
    public function addComment(Questioncomment $questioncomment): void
    {
        $this->questioncomment->attach($questioncomment);
    }
}
