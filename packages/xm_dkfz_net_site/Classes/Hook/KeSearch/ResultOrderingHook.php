<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook\KeSearch;

use Tpwd\KeSearch\Lib\Db;

class ResultOrderingHook
{
    public function getOrdering(string &$orderBy, Db $parentObject): void
    {
        if ($orderBy !== '') {
            $orderBy = 'customranking DESC, ' . $orderBy;
        } else {
            $orderBy = 'customranking DESC';
        }
    }
}
