<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class PhoneBookAbteilung
{
    public string $dkfzId = '';

    public string $nummer = '';

    public string $bezeichnung = '';

    public array $leitung = [];

    public array $sekretariat = [];

    public function __construct(string $dkfzId)
    {
        $this->dkfzId = $dkfzId;
    }
}
