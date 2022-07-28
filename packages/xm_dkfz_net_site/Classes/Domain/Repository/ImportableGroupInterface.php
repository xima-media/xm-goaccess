<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

interface ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_number: string, uid: int}>
     **/
    public function findAllGroupsWithDkfzNumber(): array;

    /**
     * @param array<string> $dkfzNumbers
     * @param int $pid
     * @param string $subgroup
     * @return int
     */
    public function bulkInsertDkfzNumbers(array $dkfzNumbers, int $pid, string $subgroup): int;

    /**
     * @param array<string> $dkfzNumbers
     * @return int
     */
    public function deleteByDkfzNumbers(array $dkfzNumbers): int;
}
