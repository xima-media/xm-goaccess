<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Place extends AbstractEntity
{
    protected string $name = '';

    protected string $function = '';

    protected string $room = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\Contact>
     */
    protected ObjectStorage $contacts;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Repository\UserGroupRepository>
     */
    protected ObjectStorage $departments;
}
