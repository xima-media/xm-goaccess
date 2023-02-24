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
     * @var ObjectStorage<Contact>|null
     */
    protected ?ObjectStorage $contacts = null;

    /**
     * @var FeGroup|null
     */
    protected ?FeGroup $feGroup = null;

    public function __construct()
    {
        $this->contacts = new ObjectStorage();
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
     * @return ObjectStorage<Contact>|null
     */
    public function getContacts(): ?ObjectStorage
    {
        return $this->contacts;
    }

    public function getFeGroup(): ?FeGroup
    {
        return $this->feGroup;
    }

    public function getFakeRoomSlug(): string
    {
        return urlencode($this->room);
    }

    public function getContactFunction(): string
    {
        foreach ($this->contacts ?? [] as $contact) {
            if ($contact->getFunction()) {
                return $contact->getFunction();
            }
        }
        return '';
    }

    /**
     * @return Contact[]
     */
    public function getPhoneContacts(): array
    {
        $phones = [];
        foreach ($this->contacts ?? [] as $contact) {
            if ($contact->getRecordType() === '0' && $contact->getNumber()) {
                $phones[] = $contact;
            }
        }
        return $phones;
    }

    /**
     * @return Contact[]
     */
    public function getFaxContacts(): array
    {
        $faxes = [];
        foreach ($this->contacts ?? [] as $contact) {
            if ($contact->getRecordType() === '1' && $contact->getNumber()) {
                $faxes[] = $contact;
            }
        }
        return $faxes;
    }
}
