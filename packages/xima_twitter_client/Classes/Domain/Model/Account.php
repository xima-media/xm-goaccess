<?php

namespace Xima\XimaTwitterClient\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Account extends AbstractEntity
{
    protected string $username = '';

    protected string $fetchType = '';

    public function __construct(string $fetchType)
    {
        $this->fetchType = $fetchType;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
