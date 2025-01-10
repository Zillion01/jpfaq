<?php

namespace Jp\Jpfaq\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***
 *
 * This file is part of the "JpFAQ" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018
 *
 ***/
/**
 * Categorycomment
 */
class Categorycomment extends AbstractEntity
{
    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * comment
     *
     * @var string
     */
    #[TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'NotEmpty'])]
    protected $comment = '';

    /**
     * ip
     *
     * @var string
     */
    protected $ip = '';

    /**
     * honeypot
     *
     * @var string
     */
    protected $finfo = '';

    /**
     * category
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Category>
     */
    #[TYPO3\CMS\Extbase\Annotation\ORM\Lazy]
    protected $category;

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
        $this->category = new ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Returns the comment
     *
     * @return string $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment
     *
     * @param string $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Returns the finfo
     *
     * @return string $finfo
     */
    public function getFinfo()
    {
        return $this->finfo;
    }

    /**
     * Sets the finfo
     *
     * @param string $finfo
     */
    public function setFinfo($finfo): void
    {
        $this->finfo = $finfo;
    }

    /**
     * Adds a Category
     *
     * @param \Jp\Jpfaq\Domain\Model\Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->category->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \Jp\Jpfaq\Domain\Model\Category $categoryToRemove The Category to be removed
     */
    public function removeCategory(Category $categoryToRemove): void
    {
        $this->category->detach($categoryToRemove);
    }

    /**
     * Returns the category
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Category> $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param  \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jp\Jpfaq\Domain\Model\Category> $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * Returns the Ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Sets the Ip
     *
     * @param string $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }
}
