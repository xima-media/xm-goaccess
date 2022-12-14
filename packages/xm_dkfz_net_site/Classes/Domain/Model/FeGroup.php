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

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param ObjectStorage<FeGroup>|null $subgroup
     */
    public function setSubgroup(?ObjectStorage $subgroup): void
    {
        $this->subgroup = $subgroup;
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
}
