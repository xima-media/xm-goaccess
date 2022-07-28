<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

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
}
