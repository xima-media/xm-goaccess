<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

class UserRepository extends \Blueways\BwGuild\Domain\Repository\UserRepository
{
    public function findAllDkfzUser()
    {
        $query = $this->createQuery();
        $query->setQuerySettings($query->getQuerySettings()->setRespectStoragePage(false));
        $query->setQuerySettings($query->getQuerySettings()->setIgnoreEnableFields(true));
        $query->matching(
            $query->logicalNot($query->equals('ad_account_name', ''))
        );
        return $query->execute();
    }
}
