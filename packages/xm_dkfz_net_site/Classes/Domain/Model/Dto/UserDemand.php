<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class UserDemand extends \Blueways\BwGuild\Domain\Model\Dto\UserDemand
{
    public string $function = '';

    public string $committee = '';

    public const SEARCH_FIELDS = 'first_name,last_name';

    public const FEGROUP_SEARCH_FIELDS = 'title,dkfz_number';

    public function __construct(
        ?string $feature = null,
        ?string $feGroup = null,
        ?string $function = null,
        ?string $committee = null
    ) {
        parent::__construct($feature, $feGroup);
        $this->function = $function ?? '';
        $this->committee = $committee ?? '';
    }
}
