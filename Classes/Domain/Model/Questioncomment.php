<?php

namespace Jp\Jpfaq\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***
 *
 * This file is part of the "jpFAQ" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018
 *
 ***/
/**
 * Questioncomment
 */
class Questioncomment extends AbstractEntity
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
    #[Validate(['validator' => 'NotEmpty'])]
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
     * question
     *
     * @var ObjectStorage<Question>
     */
    #[Lazy]
    protected $question;

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
     */
    protected function initStorageObjects()
    {
        $this->question = new ObjectStorage();
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
     * Returns the question
     *
     * @return ObjectStorage<Question> $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question
     *
     * @param ObjectStorage<Question> $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
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
