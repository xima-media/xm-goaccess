<?php

namespace Xima\XmDkfzNetSite\ResourceResolver;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

abstract class AbstractResolver
{
    protected GenericProvider $provider;

    protected GenericResourceOwner $resourceOwner;

    protected AccessToken $accessToken;

    protected string $providerId;

    /**
     * @return \League\OAuth2\Client\Provider\GenericProvider
     */
    public function getProvider(): GenericProvider
    {
        return $this->provider;
    }

    /**
     * @return \League\OAuth2\Client\Provider\GenericResourceOwner
     */
    public function getResourceOwner(): GenericResourceOwner
    {
        return $this->resourceOwner;
    }

    /**
     * @return \League\OAuth2\Client\Token\AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getProviderId(): string
    {
        return $this->providerId;
    }

    /**
     * @param \League\OAuth2\Client\Provider\GenericProvider $provider
     * @param \League\OAuth2\Client\Provider\GenericResourceOwner $resourceOwner
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     * @param string $providerId
     */
    public function __construct(
        GenericProvider $provider,
        GenericResourceOwner $resourceOwner,
        AccessToken $accessToken,
        string $providerId
    ) {
        $this->provider = $provider;
        $this->resourceOwner = $resourceOwner;
        $this->accessToken = $accessToken;
        $this->providerId = $providerId;
    }

    abstract public function updateBackendUser(array &$beUser): void;

    abstract public function updateFrontendUser(array &$beUser): void;

    abstract public function getIntendedUsername(): ?string;

    abstract public function getIntendedEmail(): ?string;
}
