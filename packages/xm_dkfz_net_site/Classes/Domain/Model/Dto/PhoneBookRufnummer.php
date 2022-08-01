<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class PhoneBookRufnummer
{
    public string $rufnummer = '';

    public string $rufnummerType = '';

    public string $abteilung = '';

    public string $raum = '';

    public string $funktion = '';

    public bool $primaernummer = false;

    public int $feGroup = 0;

    public int $foreignUid = 0;

    public string $foreignTable = '';

    public function getRecordType(): int
    {
        return $this->rufnummerType === 'Fax' ? 1 : 0;
    }
}
