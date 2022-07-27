<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class PhoneBookAbteilung
{
    public string $dkfzId = '';

    public function __construct(string $dkfzId)
    {
        $this->dkfzId = $dkfzId;
    }
}
