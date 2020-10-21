<?php

namespace Jp\Jpfaq\Controller;

/**
 * SearchController
 */
class SearchController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public function searchAction()
    {
        $this->view->assign('showNumberOfResults', (int)$this->settings['flexform']['showNumberOfResults']);
    }
}