<?php

namespace Xima\XmDkfzNetSite\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RedirectResponse;

class RedirectAfterLogout implements MiddlewareInterface
{
    public function __construct(protected ExtensionConfiguration $extensionConfiguration)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (!isset($params['logintype']) || $params['logintype'] !== 'logout') {
            return $handler->handle($request);
        }

        $returnUrl = $this->extensionConfiguration->get('xm_dkfz_net_site', 'logout_redirect_url') ?? '';

        if (!$returnUrl) {
            return $handler->handle($request);
        }

        return new RedirectResponse($returnUrl, 302);
    }
}
