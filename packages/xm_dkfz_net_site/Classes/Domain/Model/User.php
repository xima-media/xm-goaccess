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

    protected string $dkfzHash = '';

    protected bool $disable = false;

    protected int $gender = 0;

    protected ?User $representative = null;

    protected string $committee = '';

    protected string $responsibilities = '';

    /**
     * @return \Xima\XmDkfzNetSite\Domain\Model\User|null
     */
    public function getRepresentative(): ?User
    {
        return $this->representative;
    }

    /**
     * @return string
     */
    public function getCommittee(): string
    {
        return $this->committee;
    }

    /**
     * @return string
     */
    public function getResponsibilities(): string
    {
        return $this->responsibilities;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return bool
     */
    public function isDisable(): bool
    {
        return $this->disable;
    }

    /**
     * @param bool $disable
     */
    public function setDisable(bool $disable): void
    {
        $this->disable = $disable;
    }

    /**
     * @param string $room
     */
    public function setRoom(string $room): void
    {
        $this->room = $room;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @param \DateTime|null $memberSince
     */
    public function setMemberSince(?\DateTime $memberSince): void
    {
        $this->memberSince = $memberSince;
    }

    /**
     * @param \DateTime|null $birthday
     */
    public function setBirthday(?\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @param string $dkfzId
     */
    public function setDkfzId(string $dkfzId): void
    {
        $this->dkfzId = $dkfzId;
    }

    /**
     * @param string $adAccountName
     */
    public function setAdAccountName(string $adAccountName): void
    {
        $this->adAccountName = $adAccountName;
    }

    /**
     * @param string $dkfzHash
     */
    public function setDkfzHash(string $dkfzHash): void
    {
        $this->dkfzHash = $dkfzHash;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\Contact>|null $contacts
     */
    public function setContacts(?ObjectStorage $contacts): void
    {
        $this->contacts = $contacts;
    }

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return \DateTime|null
     */
    public function getMemberSince(): ?\DateTime
    {
        return $this->memberSince;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getDkfzId(): string
    {
        return $this->dkfzId;
    }

    /**
     * @return string
     */
    public function getAdAccountName(): string
    {
        return $this->adAccountName;
    }

    /**
     * @return string
     */
    public function getDkfzHash(): string
    {
        return $this->dkfzHash;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\Contact>|null
     */
    public function getContacts(): ?ObjectStorage
    {
        return $this->contacts;
    }

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Xima\XmDkfzNetSite\Domain\Model\Contact>|null
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected ?ObjectStorage $contacts = null;

    public function getDisplayName(): string
    {
        if (!$this->lastName || !$this->firstName) {
            return $this->username;
        }
        $name = $this->lastName . ', ' . $this->firstName;

        if ($this->title) {
            $name = $this->title . ' ' . $name;
        }

        return $name;
    }

    public function getContactRoom(): string
    {
        foreach ($this->contacts ?? [] as $contact) {
            if ($contact->getRoom()) {
                return $contact->getRoom();
            }
        }
        return '';
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
     * @return \Xima\XmDkfzNetSite\Domain\Model\Contact[]
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
     * @return \Xima\XmDkfzNetSite\Domain\Model\Contact[]
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

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup[]
     */
    public function getGroups()
    {
        $groups = [];
        foreach ($this->getUsergroup() as $group) {
            if ($group->getUid() !== 1) {
                $groups[] = $group;
            }
        }
        return $groups;
    }
}
