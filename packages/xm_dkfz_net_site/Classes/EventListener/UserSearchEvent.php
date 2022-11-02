<?php

namespace Xima\XmDkfzNetSite\EventListener;

use Blueways\BwGuild\Event\ModifyQueryBuilderEvent;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\MathUtility;
use Xima\XmDkfzNetSite\Domain\Model\Dto\UserDemand;

class UserSearchEvent
{
    protected QueryBuilder $queryBuilder;

    protected UserDemand $userDemand;

    protected bool $contactTableJoined = false;

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
        $this->addConstraintForPhoneNumber();
    }

    protected function addJoinOnContacts(): void
    {
        if ($this->contactTableJoined) {
            return;
        }

        $this->queryBuilder->leftjoin(
            $this->userDemand::TABLE,
            'tx_xmdkfznetsite_domain_model_contact',
            'c',
            $this->queryBuilder->expr()->eq(
                $this->userDemand::TABLE . '.uid',
                $this->queryBuilder->quoteIdentifier('c.foreign_uid')
            )
        );

        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->eq(
                'c.foreign_table',
                $this->queryBuilder->createNamedParameter($this->userDemand::TABLE, \PDO::PARAM_STR)
            )
        );

        $this->contactTableJoined = true;
    }

    protected function addConstraintForFunction(): void
    {
        if (!$this->userDemand->function) {
            return;
        }

        $this->addJoinOnContacts();

        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->like(
                'c.function',
                $this->queryBuilder->createNamedParameter('%' . $this->userDemand->function . '%')
            )
        );
    }

    protected function addConstraintForCommittee(): void
    {
        if (!$this->userDemand->committee) {
            return;
        }

        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->like(
                $this->userDemand::TABLE . '.committee',
                $this->queryBuilder->createNamedParameter('%' . $this->userDemand->committee . '%')
            )
        );
    }

    protected function addConstraintForPhoneNumber(): void
    {
        $searchTerm = $this->userDemand->search;

        if (!$searchTerm || !MathUtility::canBeInterpretedAsInteger($searchTerm)) {
            return;
        }

        // reset search termin in demand to prevent default text search in user properties
        $this->userDemand->search = '';

        $this->addJoinOnContacts();

        $this->queryBuilder->andWhere(
            $this->queryBuilder->expr()->like(
                'c.number',
                $this->queryBuilder->createNamedParameter('%' . $searchTerm . '%', \PDO::PARAM_STR)
            )
        );
    }
}
