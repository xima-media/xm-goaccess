<?php

namespace Xima\XmDkfzNetSite\ResourceResolver;

use Xima\XimaOauth2Extended\ResourceResolver\AbstractResourceResolver;

class DkfzResourceResolver extends AbstractResourceResolver
{
    public function getIntendedUsername(): ?string
    {
        return $this->resourceOwner->toArray()['email'] ?? null;
    }

    public function getIntendedEmail(): ?string
    {
        return $this->resourceOwner->toArray()['email'] ?? null;
    }

    public function updateBackendUser(array &$beUser): void
    {
        $remoteUser = $this->getResourceOwner()->toArray();

        if (!$beUser['username'] && $remoteUser['email']) {
            $beUser['username'] = $remoteUser['email'];
        }

        if ($remoteUser['email']) {
            $beUser['email'] = $remoteUser['email'];
        }

        $beUser['disable'] =  0;
        $beUser['admin'] = 0;

        if (!$beUser['realName']) {
            $beUser['realName'] = $remoteUser['unique_name'];
        }
    }

    public function updateFrontendUser(array &$feUser): void
    {
        $remoteUser = $this->getResourceOwner()->toArray();

        if (!$feUser['username'] && $remoteUser['email']) {
            $feUser['username'] = $remoteUser['email'];
        }

        if ($remoteUser['email']) {
            $feUser['email'] = $remoteUser['email'];
        }

        $feUser['disable'] =  0;

        if (!$feUser['name']) {
            $feUser['name'] = $remoteUser['unique_name'];
        }

        // @TODO: usergroups
    }
}
