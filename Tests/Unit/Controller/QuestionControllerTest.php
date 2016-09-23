<?php
namespace Jp\Jpfaq\Tests\Unit\Controller;

/**
 * Test case.
 */
class QuestionControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Jp\Jpfaq\Controller\QuestionController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMock(\Jp\Jpfaq\Controller\QuestionController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllQuestionsFromRepositoryAndAssignsThemToView()
    {

        $allQuestions = $this->getMock(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, [], [], '', false);

        $questionRepository = $this->getMock(\Jp\Jpfaq\Domain\Repository\QuestionRepository::class, ['findAll'], [], '', false);
        $questionRepository->expects(self::once())->method('findAll')->will(self::returnValue($allQuestions));
        $this->inject($this->subject, 'questionRepository', $questionRepository);

        $view = $this->getMock(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class);
        $view->expects(self::once())->method('assign')->with('questions', $allQuestions);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
