<?php
namespace Jp\Jpfaq\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/***
 *
 * This file is part of the "jpFAQ" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016
 *
 ***/

/**
 * The repository for Questions
 */
class QuestionRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

    /**
     * Find questions with constraints
     *
     * @param array $categories
     * @return array|QueryResultInterface
     */
    public function findQuestionsWithConstraints(array $categories = [])
    {
        $query = $this->createQuery();

        if (!empty($categories)) {
            # categories can be multi-valued
            foreach ($categories as $demandedCategory) {
                $constraintsCategories[] = $query->contains('categories', $demandedCategory);
            }
        }

        if (!empty($constraintsCategories)) {
            $query->matching($query->logicalOr($constraintsCategories));
        }

        return $query->execute();
    }
}
