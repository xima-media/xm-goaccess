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

        $this->addConstrainForFunction();
    }

    protected function addConstrainForFunction(): void
    {
        if (!$this->userDemand->function) {
            return;
        }
    }

}
