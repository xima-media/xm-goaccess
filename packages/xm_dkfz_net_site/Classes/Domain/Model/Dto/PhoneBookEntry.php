<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

use TYPO3\CMS\Core\Utility\MathUtility;

class PhoneBookEntry
{
    public int $id = 0;

    public string $nachname = '';

    public string $vorname = '';

    public string $anrede = '';

    public string $titel = '';

    public string $raum = '';

    public string $funktion = '';

    public string $mail = '';

    public string $adAccountName = '';

    public string $gruppen = '';

    /**
     * @var PhoneBookRufnummer[]
     */
    public array $rufnummern = [];

    /**
     * @var PhoneBookAbteilung[]
     */
    public array $abteilung = [];

    public string $usergroup = '';

    public string $cachedHash = '';

    public function isUser(): bool
    {
        return $this->adAccountName !== '';
    }

    public function getDisable(): bool
    {
        return false;
    }

    public function getHash(): string
    {
        if (!$this->cachedHash) {
            $this->cachedHash = md5(serialize($this));
        }

        return $this->cachedHash;
    }

    public function getGender(): int
    {
        $genderMapping = ['Herr' => 1, 'Frau' => 2];
        if ($this->anrede && in_array($this->anrede, $genderMapping)) {
            return $genderMapping[$this->anrede];
        }
        return 0;
    }

    public function getUsername(): string
    {
        return $this->adAccountName;
    }

    public function getFeGroupForPlace(): int
    {
        if (MathUtility::canBeInterpretedAsInteger($this->usergroup)) {
            return (int)$this->usergroup;
        }
        return 0;
    }

    /**
     * @return array<int, string>
     */
    public function getDkfzGroupIdentifierOfAbteilungen(): array
    {
        $abteilungen = [];
        foreach ($this->abteilung as $abteilung) {
            $abteilungen[] = $abteilung->getUniqueIdentifier();
        }
        return $abteilungen;
    }

    public function getCombinedName(): string
    {
        return $this->vorname . ' ' . $this->nachname;
    }

    public function isIntranetRedakteur(): bool
    {
        return $this->gruppen === 'Intranet-Redakteure';
    }
}
