<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Committee extends AbstractEntity
{
    protected string $name = '';

    public function getName(): string
    {
        return $this->name;
    }
}
