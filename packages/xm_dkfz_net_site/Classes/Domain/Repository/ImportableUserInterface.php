<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;

interface ImportableUserInterface
{
    /**
     * @return array<int, array{dkfz_id: int, dkfz_hash: string, uid: int}>
     **/
    public function findAllUsersWithDkfzId(): array;

    /**
     * @param PhoneBookEntry[] $entries
     */
    public function bulkInsertPhoneBookEntries(array $entries, int $pid): int;

    public function updateUserFromPhoneBookEntry(PhoneBookEntry $entry): int;

    /**
     * @param array<int> $dkfzIds
     * @return int
     */
    public function deleteUsersByDkfzIds(array $dkfzIds): int;

    /**
     * @return array<int, array{dkfz_id: int, dkfz_hash: string, uid: int}>
     * @param array<int> $dkfzIds
     */
    public function findByDkfzIds(array $dkfzIds): array;
}
