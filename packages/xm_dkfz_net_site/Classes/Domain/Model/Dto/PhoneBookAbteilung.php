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

    /**
     * @var PhoneBookAbteilungPerson[]
     */
    public array $koordination = [];

    /**
     * @var PhoneBookAbteilungPerson[]
     */
    public array $assistenz = [];

    public string $secretaries = '';

    public string $managers = '';

    public string $coordinators = '';

    public string $assistants = '';

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

    public function getUniqueIdentifier(): ?string
    {
        if (!$this->nummer || !$this->bezeichnung) {
            return null;
        }
        return md5($this->nummer . '_' . $this->bezeichnung);
    }
}
