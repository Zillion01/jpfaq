<?php

namespace Jp\Jpfaq\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for Questions
 */
class QuestionRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Find questions with constraints
     *
     * @param array $categories
     * @param bool $excludeAlreadyDisplayedQuestions
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findQuestionsWithConstraints(array $categories = [], bool $excludeAlreadyDisplayedQuestions = false): QueryResultInterface|array
    {
        $query = $this->createQuery();
        $constraintsOr = [];
        $constraintsAnd = [];

        if (!empty($categories)) {
            // categories can be multi-valued, show questions which belong to one of the choosen categories
            foreach ($categories as $demandedCategory) {
                $constraintsOr[] = $query->contains('categories', $demandedCategory);
            }
        }

        if ($excludeAlreadyDisplayedQuestions && isset($GLOBALS['EXT']['jpfaq']['alreadyDisplayed']) && !empty($GLOBALS['EXT']['jpfaq']['alreadyDisplayed'])) {
            $constraintsAnd[] = $query->logicalNot(
                $query->in(
                    'uid',
                    $GLOBALS['EXT']['jpfaq']['alreadyDisplayed']
                )
            );
        }

        if (!empty($constraintsOr) && !empty($constraintsAnd)) {
            $query->matching($query->logicalAnd(
                $query->logicalOr(...$constraintsOr),
                $query->logicalOr(...$constraintsAnd)
            ));
        } elseif (!empty($constraintsOr)) {
            $query->matching($query->logicalOr(...$constraintsOr));
        } elseif (!empty($constraintsAnd)) {
            $query->matching($query->logicalAnd(...$constraintsAnd));
        }

        return $query->execute();
    }
}
