<?php

namespace Jp\Jpfaq\Controller;

use Jp\Jpfaq\Domain\Repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;

/**
 * CategoryController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * action list
     *
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $categories = $this->categoryRepository->findAll();

        $this->view->assign('categories', $categories);

        return $this->htmlResponse();
    }
}
