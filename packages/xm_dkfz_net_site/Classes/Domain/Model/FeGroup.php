<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class FeGroup extends AbstractEntity
{
    /**
     * @var ObjectStorage<User>|null
     */
    protected ?ObjectStorage $secretaries = null;

    /**
     * @var ObjectStorage<User>|null
     */
    protected ?ObjectStorage $managers = null;

    /**
     * @var ObjectStorage<User>|null
     */
    protected ?ObjectStorage $assistants = null;

    /**
     * @var ObjectStorage<User>|null
     */
    protected ?ObjectStorage $coordinators = null;

    protected string $dkfzNumber = '';

    protected string $title = '';

    protected string $description = '';

    /**
     * @var ObjectStorage<FeGroup>|null
     */
    protected ?ObjectStorage $subgroup = null;

    public function __construct(string $title = '')
    {
        $this->setTitle($title);
        $this->subgroup = new ObjectStorage();
    }

    public function getDkfzNumber(): string
    {
        return $this->dkfzNumber;
    }

    /**
     * @return ObjectStorage<User>|null
     */
    public function getSecretaries(): ?ObjectStorage
    {
        return $this->secretaries;
    }

    /**
     * @param ObjectStorage<User>|null $secretaries
     */
    public function setSecretaries(?ObjectStorage $secretaries): void
    {
        $this->secretaries = $secretaries;
    }

    /**
     * @return ObjectStorage<User>|null
     */
    public function getManagers(): ?ObjectStorage
    {
        return $this->managers;
    }

    /**
     * @param ObjectStorage<User>|null $managers
     */
    public function setManagers(?ObjectStorage $managers): void
    {
        $this->managers = $managers;
    }

    public function getAssistants(): ?ObjectStorage
    {
        return $this->assistants;
    }

    public function setAssistants(?ObjectStorage $assistants): void
    {
        $this->assistants = $assistants;
    }

    public function getCoordinators(): ?ObjectStorage
    {
        return $this->coordinators;
    }

    public function setCoordinators(?ObjectStorage $coordinators): void
    {
        $this->coordinators = $coordinators;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param FeGroup|null $subgroup
     */
    public function addSubgroup(?FeGroup $subgroup): void
    {
        $this->subgroup->attach($subgroup);
    }

    /**
     * @param FeGroup|null $subgroup
     */
    public function removeSubgroup(?FeGroup $subgroup): void
    {
        $this->subgroup->detach($subgroup);
    }

    /**
     * @return ObjectStorage<FeGroup>|null
     */
    public function getSubgroup(): ?ObjectStorage
    {
        return $this->subgroup;
    }

    /**
     * @param ObjectStorage<FeGroup>|null $subgroup
     */
    public function setSubgroup(?ObjectStorage $subgroup): void
    {
        $this->subgroup = $subgroup;
    }

    public function getFakeSlug(): string
    {
        $identifier = $this->dkfzNumber ?: $this->title;
        return urlencode($identifier);
    }
}
