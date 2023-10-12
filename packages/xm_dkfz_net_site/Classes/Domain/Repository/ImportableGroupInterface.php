<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;

interface ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_group_identifier: string, uid: int, dkfz_hash: string}>
     **/
    public function findAllGroupsWithDkfzGroupIdentifier(): array;

    /**
     * @param array<PhoneBookAbteilung> $phoneBookAbteilungen
     * @param int $pid
     * @param array<int, array{title: string, uid: int}> $fileMounts
     * @return int
     */
    public function bulkInsertPhoneBookAbteilungen(array $phoneBookAbteilungen, int $pid, array $fileMounts): int;

    /**
     * @param array<string> $identifiers
     * @return int
     */
    public function deleteByDkfzGroupIdentifiers(array $identifiers): int;

    /**
     * @return array<int, array{title: string, uid: int}>
     */
    public function findAllFileMounts(): array;

    /**
     * @param string[] $dkfzNumbers
     * @param string $basePath
     * @return int
     */
    public function bulkInsertFileMounts(array $dkfzNumbers, string $basePath): int;

    public function updateFromPhoneBookEntry(PhoneBookAbteilung $entry): int;
}
