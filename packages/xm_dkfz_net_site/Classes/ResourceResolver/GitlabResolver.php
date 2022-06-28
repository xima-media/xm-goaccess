<?php

namespace Xima\XmDkfzNetSite\ResourceResolver;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

class GitlabResolver extends AbstractResolver
{

    public function getIntendedUsername(): ?string
    {
        return $this->resourceOwner->toArray()['username'] ?? null;
    }

    public function getIntendedEmail(): ?string
    {
        return $this->resourceOwner->toArray()['email'] ?? null;
    }

    public function updateBackendUser(array &$beUser): void
    {
        $remoteUser = $this->getResourceOwner()->toArray();

        if (!$beUser['username'] && $remoteUser['username']) {
            $beUser['username'] = $remoteUser['username'];
        }

        if ($remoteUser['email']) {
            $beUser['email'] = $remoteUser['email'];
        }

        if ($remoteUser['state'] === 'active') {
            $beUser['disable'] =  0;
        }

        if (!$beUser['realName']) {
            $beUser['realName'] = $remoteUser['name'];
        }

        if (!$beUser['admin'] && $remoteUser['is_admin']) {
            $beUser['admin'] = 1;
        }
    }

    public function updateFrontendUser(array &$beUser): void
    {
        // TODO: Implement updateFrontendUser() method.
    }
}
