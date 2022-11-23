<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Contact extends AbstractEntity
{
    protected string $recordType = '';

    protected string $function = '';

    protected string $room = '';

    protected bool $primaryNumber = false;

    protected string $number = '';

    protected ?FrontendUserGroup $feGroup = null;

    /**
     * @return string
     */
    public function getRecordType(): string
    {
        return $this->recordType;
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
     * @return bool
     */
    public function isPrimaryNumber(): bool
    {
        return $this->primaryNumber;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return FrontendUserGroup|null
     */
    public function getFeGroup(): ?FrontendUserGroup
    {
        return $this->feGroup;
    }

    public function getCleanNumber(): string
    {
        return str_replace(' ', '', $this->number);
    }
}
