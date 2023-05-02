<?php

namespace Xima\XmDkfzNetSite\ResourceProvider;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

class DkfzResourceProvider extends GenericProvider
{
    /**
     * @return string[]
     */
    protected function getRequiredOptions(): array
    {
        return [
            'urlAuthorize',
            'urlAccessToken',
        ];
    }

    /**
     * @param AccessToken $token
     * @return array<mixed>
     */
    protected function fetchResourceOwnerDetails(AccessToken $token): array
    {
        $tokenValues = $token->getValues();
        return (array)json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $tokenValues['id_token'])[1]))));
    }
}
