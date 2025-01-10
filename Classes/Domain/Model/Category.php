<?php

namespace Jp\Jpfaq\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Category
 */
class Category extends AbstractEntity
{
    /**
     * category
     *
     *
     * @var string
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected $category = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Returns the category
     *
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param string $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
}
