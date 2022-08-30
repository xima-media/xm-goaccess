<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class BeUser extends AbstractEntity
{
    protected string $dkfzId = '';

    protected string $adAccountName = '';

    protected string $dkfzHash = '';

    /**
     * @return string
     */
    public function getDkfzId(): string
    {
        return $this->dkfzId;
    }

    /**
     * @param string $dkfzId
     */
    public function setDkfzId(string $dkfzId): void
    {
        $this->dkfzId = $dkfzId;
    }

    /**
     * @return string
     */
    public function getAdAccountName(): string
    {
        return $this->adAccountName;
    }

    /**
     * @param string $adAccountName
     */
    public function setAdAccountName(string $adAccountName): void
    {
        $this->adAccountName = $adAccountName;
    }

    /**
     * @return string
     */
    public function getDkfzHash(): string
    {
        return $this->dkfzHash;
    }

    /**
     * @param string $dkfzHash
     */
    public function setDkfzHash(string $dkfzHash): void
    {
        $this->dkfzHash = $dkfzHash;
    }
}
