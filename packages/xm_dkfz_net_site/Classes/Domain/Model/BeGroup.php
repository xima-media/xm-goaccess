<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class BeGroup extends AbstractEntity
{
    protected string $username = '';

    protected string $email = '';

    protected string $realName = '';

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getRealName(): string
    {
        return $this->realName;
    }

    /**
     * @param string $realName
     */
    public function setRealName(string $realName): void
    {
        $this->realName = $realName;
    }
}
