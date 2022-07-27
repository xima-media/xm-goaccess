<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

class PhoneBookCompareResult
{
    /**
     * @var int[]|string[]
     */
    public array $dkfzIdsToCreate = [];

    /**
     * @var int[]|string[]
     */
    public array $dkfzIdsToUpdate = [];

    /**
     * @var int[]|string[]
     */
    public array $dkfzIdsToDelete = [];

    /**
     * @var int[]|string[]
     */
    public array $dkfzIdsToSkip = [];

    /**
     * @var PhoneBookPerson[]
     */
    public array $phoneBookUsersById = [];

    /**
     * @return \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson[]
     */
    public function getPhoneBookPersonsForAction(string $actionName): array
    {
        if (!in_array($actionName, ['update', 'create'])) {
            return [];
        }

        $idsToFilter = $actionName === 'update' ? $this->dkfzIdsToUpdate : $this->dkfzIdsToCreate;

        return array_filter(
            $this->phoneBookUsersById,
            function ($id) use ($idsToFilter) {
                return in_array($id, $idsToFilter);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
