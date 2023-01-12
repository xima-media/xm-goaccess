<?php

namespace Xima\XimaTwitterClient\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class AccountRepository extends Repository
{
    public function findAllIgnorePid()
    {
        $query = $this->createQuery();
        $query->setQuerySettings($query->getQuerySettings()->setRespectStoragePage(false));
        return $query->execute();
    }
}
