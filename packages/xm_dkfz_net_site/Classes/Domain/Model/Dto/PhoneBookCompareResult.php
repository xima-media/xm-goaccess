<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class PhoneBookCompareResult
{
    /**
     * @var int[]
     */
    public array $dkfzIdsToCreate = [];

    /**
     * @var int[]
     */
    public array $dkfzIdsToUpdate = [];

    /**
     * @var int[]
     */
    public array $dkfzIdsToDelete = [];

    /**
     * @var int[]
     */
    public array $dkfzIdsToSkip = [];

    /**
     * @var string[]
     */
    public array $dkfzGroupIdentifierToCreate = [];

    /**
     * @var string[]
     */
    public array $dkfzGroupIdentifiersToUpdate = [];

    /**
     * @var string[]
     */
    public array $dkfzGroupIdentifiersToDelete = [];

    /**
     * @var string[]
     */
    public array $dkfzGroupIdentifiersToSkip = [];
}
