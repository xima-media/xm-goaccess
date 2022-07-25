<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;

interface ImportableUserInterface
{
    /**
     * @return array<int, array{dkfz_id: string, dkfz_hash: string}>
     **/
    public function findAllUsersWithDkfzId(): array;

    /**
     * @param PhoneBookPerson[] $persons
     */
    public function bulkInsertFromPhoneBook(array $persons, int $pid): int;

    public function updateUserFromPhoneBook(PhoneBookPerson $person): int;

    /**
     * @param array<string|int> $dkfzIds
     * @return int
     */
    public function deleteUserByDkfzIds(array $dkfzIds): int;
}
