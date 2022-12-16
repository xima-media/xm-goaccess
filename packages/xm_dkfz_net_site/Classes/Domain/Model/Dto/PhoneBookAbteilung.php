<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class PhoneBookAbteilung
{
    public string $dkfzId = '';

    public string $nummer = '';

    public string $bezeichnung = '';

    public string $cachedHash = '';

    /**
     * @var PhoneBookAbteilungPerson[]
     */
    public array $leitung = [];

    /**
     * @var PhoneBookAbteilungPerson[]
     */
    public array $sekretariat = [];

    public function __construct(string $dkfzId)
    {
        $this->dkfzId = $dkfzId;
    }

    public function getHash(): string
    {
        if (!$this->cachedHash) {
            $this->cachedHash = md5(serialize($this));
        }

        return $this->cachedHash;
    }
}
