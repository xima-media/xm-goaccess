<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\ElementAttributesInterface;

class UniqueVisitorsButtonProvider implements ButtonProviderInterface, ElementAttributesInterface
{
    public function getTitle(): string
    {
        return 'button title';
    }

    public function getLink(): string
    {
        return '#';
    }

    public function getTarget(): string
    {
        return '_blank';
    }

    public function getElementAttributes(): array
    {
        return [
            'data-dispatch-action' => 'TYPO3.ModuleMenu.showModule',
            'data-dispatch-args-list' => 'system_BelogLog,&'
                . http_build_query(['tx_belog_system_beloglog' => ['constraint' => ['level' => 'notice']]]),
        ];
    }
}
