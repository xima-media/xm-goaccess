<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Place extends AbstractEntity
{
    protected string $name = '';

    protected string $function = '';

    protected string $room = '';

    protected string $mail = '';

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\Contact>|null
     */
    protected ?ObjectStorage $contacts = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>|null
     */
    protected ?ObjectStorage $feGroups = null;

    public function __construct()
    {
        $this->contacts = new ObjectStorage();
        $this->feGroups = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\Contact>|null
     */
    public function getContacts(): ?ObjectStorage
    {
        return $this->contacts;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>|null
     */
    public function getFeGroups(): ?ObjectStorage
    {
        return $this->feGroups;
    }
}
