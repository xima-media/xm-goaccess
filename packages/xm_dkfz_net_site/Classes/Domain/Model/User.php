<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
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

    protected ?User $representative2 = null;

    protected ?Committee $committee = null;

    protected string $responsibilities = '';

    protected ?User $committeeRepresentative = null;

    protected ?User $committeeRepresentative2 = null;

    protected int $crdate = 0;

    protected string $committeeResponsibilities = '';

    protected string $about = '';

    /**
     * @var ObjectStorage<Contact>|null
     * @Lazy
     */
    protected ?ObjectStorage $contacts = null;

    public function getCrdate(): int
    {
        return $this->crdate;
    }

    public function setCrdate(int $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getCommitteeRepresentative(): ?User
    {
        return $this->committeeRepresentative;
    }

    public function setCommitteeRepresentative(?User $committeeRepresentative): void
    {
        $this->committeeRepresentative = $committeeRepresentative;
    }

    public function getRepresentative2(): ?User
    {
        return $this->representative2;
    }

    public function setRepresentative2(?User $representative2): void
    {
        $this->representative2 = $representative2;
    }

    public function getCommitteeRepresentative2(): ?User
    {
        return $this->committeeRepresentative2;
    }

    public function setCommitteeRepresentative2(?User $committeeRepresentative2): void
    {
        $this->committeeRepresentative2 = $committeeRepresentative2;
    }

    public function getCommitteeResponsibilities(): string
    {
        return $this->committeeResponsibilities;
    }

    public function setCommitteeResponsibilities(string $committeeResponsibilities): void
    {
        $this->committeeResponsibilities = $committeeResponsibilities;
    }

    public function getResponsibilities(): string
    {
        return $this->responsibilities;
    }

    public function setResponsibilities(string $responsibilities): void
    {
        $this->responsibilities = $responsibilities;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    public function getCommittee(): ?Committee
    {
        return $this->committee;
    }

    public function setCommittee(?Committee $committee): void
    {
        $this->committee = $committee;
    }

    public function isDisable(): bool
    {
        return $this->disable;
    }

    public function setDisable(bool $disable): void
    {
        $this->disable = $disable;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getMemberSince(): ?\DateTime
    {
        return $this->memberSince;
    }

    public function setMemberSince(?\DateTime $memberSince): void
    {
        $this->memberSince = $memberSince;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getDkfzId(): string
    {
        return $this->dkfzId;
    }

    public function setDkfzId(string $dkfzId): void
    {
        $this->dkfzId = $dkfzId;
    }

    public function getAdAccountName(): string
    {
        return $this->adAccountName;
    }

    public function setAdAccountName(string $adAccountName): void
    {
        $this->adAccountName = $adAccountName;
    }

    public function getDkfzHash(): string
    {
        return $this->dkfzHash;
    }

    public function setDkfzHash(string $dkfzHash): void
    {
        $this->dkfzHash = $dkfzHash;
    }

    /**
     * @return ObjectStorage<Contact>|null
     */
    public function getContacts(): ?ObjectStorage
    {
        return $this->contacts;
    }

    /**
     * @param ObjectStorage<Contact>|null $contacts
     */
    public function setContacts(?ObjectStorage $contacts): void
    {
        $this->contacts = $contacts;
    }

    public function removeContact(Contact $contact): void
    {
        $this->contacts->detach($contact);
    }

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

    public function getContactRoomFakeSlug(): string
    {
        $identifier = $this->getContactRoom();
        return urlencode($identifier);
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

    public function getRoom(): string
    {
        return $this->room;
    }

    public function setRoom(string $room): void
    {
        $this->room = $room;
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

    /**
     * @return FrontendUserGroup[]
     */
    public function getGroups(): array
    {
        $groups = [];
        foreach ($this->getUsergroup() as $group) {
            if ($group->getUid() !== 1) {
                $groups[] = $group;
            }
        }
        return $groups;
    }

    public function getRepresentative(): ?User
    {
        return $this->representative;
    }

    public function setRepresentative(?User $representative): void
    {
        $this->representative = $representative;
    }

    public function getAbout(): string
    {
        return $this->about;
    }

    public function setAbout(string $about): void
    {
        $this->about = $about;
    }
}
