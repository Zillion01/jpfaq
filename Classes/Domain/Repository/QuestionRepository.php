<?php

namespace Jp\Jpfaq\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
     * @param bool $excludeAlreadyDisplayedQuestions
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     * @return array|QueryResultInterface
     */
    public function findQuestionsWithConstraints(array $categories = [], bool $excludeAlreadyDisplayedQuestions = false)
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

        if ($excludeAlreadyDisplayedQuestions && isset($GLOBALS['EXT']['jpfaq']['alreadyDisplayed']) && !empty($GLOBALS['EXT']['jpfaq']['alreadyDisplayed'])) {
            $query->matching($query->logicalNot(
                $query->in(
                    'uid',
                    $GLOBALS['EXT']['jpfaq']['alreadyDisplayed']
                )
            ));
        }

        return $query->execute();
    }
}
