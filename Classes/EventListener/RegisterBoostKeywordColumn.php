<?php
declare(strict_types=1);
namespace Tpwd\KeSearchPremium\EventListener;

use Tpwd\KeSearch\Event\MatchColumnsEvent;
use Tpwd\KeSearch\Lib\SearchHelper;

class RegisterBoostKeywordColumn
{
    public function __invoke(MatchColumnsEvent $event)
    {
        $extConfPremium = SearchHelper::getExtConfPremium();
        if ($extConfPremium['enableBoostKeywords'] ?? '') {
            $event->setMatchColumns($event->getMatchColumns() . ',boostkeywords');
        }
    }
}