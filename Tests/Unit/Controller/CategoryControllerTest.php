<?php
namespace Jp\Jpfaq\Tests\Unit\Controller;

/**
 * Test case.
 */
class CategoryControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Jp\Jpfaq\Controller\CategoryController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\Jp\Jpfaq\Controller\CategoryController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllCategoriesFromRepositoryAndAssignsThemToView()
    {

        $allCategories = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $categoryRepository = $this->getMock(\Jp\Jpfaq\Domain\Repository\CategoryRepository::class, ['findAll'], [], '', false);
        $categoryRepository->expects(self::once())->method('findAll')->will(self::returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::once())->method('assign')->with('categories', $allCategories);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
