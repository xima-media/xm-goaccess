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

    /**
     * @param array<array{dkfz_id: string, uid: int}> $dbGroups
     * @return void
     */
    public function addFeUserGroupRelationToPhoneBookUsers(array $dbGroups): void
    {
        foreach ($this->phoneBookUsersById as $person) {
            $filteredDbGroups = array_filter($dbGroups, function ($dbGroup) use ($person) {
                return in_array($dbGroup['dkfz_id'], $person->getAbteilungIds());
            });
            $dbGroupUids = array_map(function ($dbGroup) {
                return $dbGroup['uid'];
            }, $filteredDbGroups);
            $person->usergroup = implode(',', $dbGroupUids);
        }
    }
}
