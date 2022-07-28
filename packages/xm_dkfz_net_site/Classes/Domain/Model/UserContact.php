<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class UserContact extends AbstractEntity
{
    protected string $recordType = '';

    protected string $position = '';

    protected string $room = '';

    protected bool $primaryNumber = false;

    protected string $number = '';

    protected ?FrontendUserGroup $feGroup = null;
}
