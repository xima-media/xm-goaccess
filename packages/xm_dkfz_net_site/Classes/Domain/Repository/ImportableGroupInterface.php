<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;

interface ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_number: string, uid: int, dkfz_hash: string}>
     **/
    public function findAllGroupsWithDkfzNumber(): array;

    /**
     * @param array<PhoneBookAbteilung> $phoneBookAbteilungen
     * @param int $pid
     * @param array<int, array{title: string, uid: int}> $fileMounts
     * @return int
     */
    public function bulkInsertPhoneBookAbteilungen(array $phoneBookAbteilungen, int $pid, array $fileMounts): int;

    /**
     * @param array<string> $dkfzNumbers
     * @return int
     */
    public function deleteByDkfzNumbers(array $dkfzNumbers): int;

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
