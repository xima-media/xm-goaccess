<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

interface ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_id: string, uid: int}>
     **/
    public function findAllGroupsWithDkfzId(): array;

    /**
     * @param array<string|int> $dkfzIds
     * @param int $pid
     * @param string $subgroup
     * @return int
     */
    public function bulkInsertDkfzIds(array $dkfzIds, int $pid, string $subgroup): int;

    /**
     * @param array<string|int> $dkfzIds
     * @return int
     */
    public function deleteByDkfzIds(array $dkfzIds): int;
}
