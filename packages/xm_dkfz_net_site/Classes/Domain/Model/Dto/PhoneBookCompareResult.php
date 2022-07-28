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
    public array $dkfzNumbersToCreate = [];

    /**
     * @var string[]
     */
    public array $dkfzNumbersToUpdate = [];

    /**
     * @var string[]
     */
    public array $dkfzNumbersToDelete = [];

    /**
     * @var string[]
     */
    public array $dkfzNumbersToSkip = [];

}
