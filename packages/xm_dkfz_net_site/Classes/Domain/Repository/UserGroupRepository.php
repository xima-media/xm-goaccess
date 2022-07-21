<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserGroupRepository extends \Blueways\BwGuild\Domain\Repository\UserGroupRepository
{
    public function findAllGroupsWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        return $qb->select('title', 'dkfz_id')
            ->from('fe_groups')
            ->where(
                $qb->expr()->neq('dkfz_id', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute()
            ->fetchAllAssociative();
    }
}
