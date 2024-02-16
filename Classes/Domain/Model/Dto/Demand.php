<?php

namespace Xima\XmGoaccess\Domain\Model\Dto;

use TYPO3\CMS\Extbase\Mvc\Request;

class Demand
{
    public bool $showIgnored = false;

    public bool $showPages = true;

    public bool $showActions = true;
    public bool $showRedirects = false;

    public static function createFromRequest(Request $request): self
    {
        $demand = new self();
        $body = $request->getParsedBody();

        $postData = $body['tx_xmgoaccess_system_xmgoaccessgoaccess'] ?? [];

        if (isset($postData['showIgnored']) && (int)$postData['showIgnored']) {
            $demand->showIgnored = true;
        }

        if (isset($postData['showPages']) && !(int)$postData['showPages']) {
            $demand->showPages = false;
        }

        if (isset($postData['showActions']) && !(int)$postData['showActions']) {
            $demand->showActions = false;
        }

        if (isset($postData['showRedirects']) && (int)$postData['showRedirects']) {
            $demand->showRedirects = true;
        }

        return $demand;
    }
}
