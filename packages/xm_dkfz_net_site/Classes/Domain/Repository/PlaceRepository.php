<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;
use Xima\XmDkfzNetSite\Domain\Model\Place;

/**
 * @extends Repository<Place>
 */
class PlaceRepository extends Repository implements ImportableUserInterface
{
    /**
     * @return array<int, array{dkfz_id: int, dkfz_hash: string, uid: int}>
     * @throws DBALException
     * @throws Exception
     */
    public function findAllUsersWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_place');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash', 'uid')
            ->from('tx_xmdkfznetsite_domain_model_place')
            ->where(
                $qb->expr()->neq('dkfz_id', 0)
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    /**
     * @param PhoneBookEntry[] $entries
     */
    public function bulkInsertPhoneBookEntries(array $entries, int $pid): int
    {
        if (!count($entries)) {
            return 0;
        }

        $rows = array_map(function ($user) use ($pid) {
            return [
                $user->getHash(),
                $user->getDisable(),
                $user->id,
                $user->nachname,
                $user->funktion,
                $user->getFeGroupForPlace(),
                $user->raum,
                count($user->rufnummern),
                $user->mail,
                $pid,
            ];
        }, $entries);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xmdkfznetsite_domain_model_place');
        return $connection->bulkInsert(
            'tx_xmdkfznetsite_domain_model_place',
            $rows,
            [
                'dkfz_hash',
                'hidden',
                'dkfz_id',
                'name',
                'function',
                'fe_group',
                'room',
                'contacts',
                'mail',
                'pid',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_BOOL,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
            ]
        );
    }

    public function updateUserFromPhoneBookEntry(PhoneBookEntry $entry): int
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_xmdkfznetsite_domain_model_place')
            ->update(
                'tx_xmdkfznetsite_domain_model_place',
                [
                    'dkfz_hash' => $entry->getHash(),
                    'hidden' => $entry->getDisable(),
                    'name' => $entry->nachname,
                    'function' => $entry->funktion,
                    'fe_group' => $entry->getFeGroupForPlace(),
                    'room' => $entry->raum,
                    'contacts' => count($entry->rufnummern),
                    'mail' => $entry->mail,
                ],
                ['dkfz_id' => $entry->id],
                [
                    Connection::PARAM_STR,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_INT,
                    Connection::PARAM_STR,
                    Connection::PARAM_INT,
                    Connection::PARAM_STR,
                ]
            );
    }

    /**
     * @throws DBALException
     */
    public function deleteUsersByDkfzIds(array $dkfzIds): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_place');
        $qb->getRestrictions()->removeAll();

        return $qb->delete('tx_xmdkfznetsite_domain_model_place')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->executeStatement();
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    public function findByDkfzIds(array $dkfzIds): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_place');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash', 'uid')
            ->from('tx_xmdkfznetsite_domain_model_place')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    /**
     * @param array<int, int|string> $uids
     * @return object[]|QueryResultInterface<Place>
     * @throws InvalidQueryException
     */
    public function findByUids(array $uids): array|QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setQuerySettings($query->getQuerySettings()->setRespectStoragePage(false));
        $query->matching(
            $query->in('uid', $uids)
        );

        return $query->execute();
    }

    /**
     * @param mixed $demand
     * @return Place[]
     * @throws Exception|DBALException
     */
    public function findByDemandArray(array $demand): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_place');
        $qb->getRestrictions()->removeAll();

        $query = $qb->select('p.*')
            ->from('tx_xmdkfznetsite_domain_model_place', 'p');

        if (isset($demand['search']) && $demand['search']) {
            $qb->where($qb->expr()->orX(
                $qb->expr()->like('p.name', $qb->createNamedParameter($demand['search'] . '%')),
                $qb->expr()->like('p.function', $qb->createNamedParameter($demand['search'] . '%'))
            ));
        }

        if (isset($demand['feGroup']) && $demand['feGroup']) {
            $qb->innerJoin(
                'p',
                'fe_groups',
                'g',
                $qb->expr()->eq('p.fe_group', 'g.uid')
            );
            $qb->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('g.uid', $qb->quoteIdentifier('p.fe_group')),
                    $qb->expr()->orX(
                        $qb->expr()->like('g.dkfz_number', $qb->createNamedParameter($demand['feGroup'] . '%')),
                        $qb->expr()->like('g.title', $qb->createNamedParameter($demand['feGroup'] . '%'))
                    )
                )
            );
        }

        $query = $query->execute();

        if (!$query instanceof Result) {
            return [];
        }

        $result = $query->fetchAllAssociative();
        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        return $dataMapper->map(Place::class, $result);
    }
}
