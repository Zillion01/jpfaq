<?php
namespace Jp\Jpfaq\Tests\Unit\Domain\Model;

/**
 * Test case.
 */
class QuestionTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Jp\Jpfaq\Domain\Model\Question
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \Jp\Jpfaq\Domain\Model\Question();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getQuestionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getQuestion()
        );

    }

    /**
     * @test
     */
    public function setQuestionForStringSetsQuestion()
    {
        $this->subject->setQuestion('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'question',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAnswerReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAnswer()
        );

    }

    /**
     * @test
     */
    public function setAnswerForStringSetsAnswer()
    {
        $this->subject->setAnswer('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'answer',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCategoriesReturnsInitialValueForCategory()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCategories()
        );

    }

    /**
     * @test
     */
    public function setCategoriesForObjectStorageContainingCategorySetsCategories()
    {
        $category = new \Jp\Jpfaq\Domain\Model\Category();
        $objectStorageHoldingExactlyOneCategories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneCategories->attach($category);
        $this->subject->setCategories($objectStorageHoldingExactlyOneCategories);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneCategories,
            'categories',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addCategoryToObjectStorageHoldingCategories()
    {
        $category = new \Jp\Jpfaq\Domain\Model\Category();
        $categoriesObjectStorageMock = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, ['attach'], [], '', false);
        $categoriesObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($category));
        $this->inject($this->subject, 'categories', $categoriesObjectStorageMock);

        $this->subject->addCategory($category);
    }

    /**
     * @test
     */
    public function removeCategoryFromObjectStorageHoldingCategories()
    {
        $category = new \Jp\Jpfaq\Domain\Model\Category();
        $categoriesObjectStorageMock = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, ['detach'], [], '', false);
        $categoriesObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($category));
        $this->inject($this->subject, 'categories', $categoriesObjectStorageMock);

        $this->subject->removeCategory($category);

    }
}
