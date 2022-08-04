<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;

interface ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_number: string, uid: int}>
     **/
    public function findAllGroupsWithDkfzNumber(): array;

    /**
     * @param array<PhoneBookAbteilung> $phoneBookAbteilungen
     * @param int $pid
     * @param string $subgroup
     * @return int
     */
    public function bulkInsertPhoneBookAbteilungen(array $phoneBookAbteilungen, int $pid, string $subgroup): int;

    /**
     * @param array<string> $dkfzNumbers
     * @return int
     */
    public function deleteByDkfzNumbers(array $dkfzNumbers): int;
}
