<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class User extends \Blueways\BwGuild\Domain\Model\User
{
    protected string $room = '';

    protected string $location = '';

    protected ?\DateTime $memberSince = null;

    protected ?\DateTime $birthday = null;

    protected string $dkfzId = '';

    protected string $adAccountName = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\UserContact>|null
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected ?ObjectStorage $contacts = null;

}
