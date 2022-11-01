<?php

namespace Xima\XmDkfzNetSite\EventListener;

use Blueways\BwGuild\Event\ModifyQueryBuilderEvent;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use Xima\XmDkfzNetSite\Domain\Model\Dto\UserDemand;
use function PHPUnit\Framework\isInstanceOf;

class UserSearchEvent
{
    protected QueryBuilder $queryBuilder;

    protected UserDemand $userDemand;

    public function __invoke(ModifyQueryBuilderEvent $event): void
    {
        if (!$event->getDemand() instanceof UserDemand) {
            return;
        }

        /** @var UserDemand $demand */
        $demand = $event->getDemand();
        $this->userDemand = $demand;
        $this->queryBuilder = $event->getQueryBuilder();

        $this->addConstraintForFunction();
        $this->addConstraintForCommittee();
    }

    protected function addConstraintForFunction(): void
    {
        if (!$this->userDemand->function) {
            return;
        }

        $this->queryBuilder->join($this->userDemand::TABLE, 'tx_xmdkfznetsite_domain_model_contact', 'c',
            $this->queryBuilder->expr()->andX(
                $this->queryBuilder->expr()->eq($this->userDemand::TABLE . '.uid',
                    $this->queryBuilder->quoteIdentifier('c.foreign_uid')),
                $this->queryBuilder->expr()->eq('c.foreign_table',
                    $this->queryBuilder->createNamedParameter($this->userDemand::TABLE, \PDO::PARAM_STR))
            )
        );

        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->like('c.function',
                $this->queryBuilder->createNamedParameter('%' . $this->userDemand->function . '%'))
        );
    }

    protected function addConstraintForCommittee(): void
    {
        if (!$this->userDemand->committee) {
            return;
        }

        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->like($this->userDemand::TABLE . '.committee',
                $this->queryBuilder->createNamedParameter('%' . $this->userDemand->committee . '%'))
        );
    }

}
