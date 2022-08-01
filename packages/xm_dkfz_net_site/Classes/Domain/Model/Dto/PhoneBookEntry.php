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

    public bool $adAccountGesperrt = true;

    public bool $deaktiviert = true;

    /**
     * @var PhoneBookRufnummer[]
     */
    public array $rufnummern = [];

    /**
     * @var PhoneBookAbteilung[]
     */
    public array $abteilung = [];

    public string $usergroup = '';

    public function isUser(): bool
    {
        return $this->adAccountName !== '';
    }

    public function getDisable(): bool
    {
        return !($this->adAccountGesperrt || $this->deaktiviert);
    }

    public function getHash(): string
    {
        return md5(serialize($this));
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
    public function getNumbersOfAbteilungen(): array
    {
        $abteilungen = [];
        foreach ($this->abteilung as $abteilung) {
            $abteilungen[] = $abteilung->nummer;
        }
        return $abteilungen;
    }

}
