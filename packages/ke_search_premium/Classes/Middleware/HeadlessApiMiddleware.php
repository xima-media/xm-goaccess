<?php
namespace Tpwd\KeSearchPremium\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tpwd\KeSearch\Lib\SearchHelper;
use Tpwd\KeSearchPremium\Headless\HeadlessApi;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;

class HeadlessApiMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($request->getQueryParams()['tx_kesearch_pi1']['headless_ce'])) {
            return $handler->handle($request);
        }

        $conf = SearchHelper::getExtConfPremium();
        $contentElementUid = intval($request->getQueryParams()['tx_kesearch_pi1']['headless_ce']) ?? 0;

        // hook for custom modifications of the configuration
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search_premium']['modifyHeadlessConfiguration'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search_premium']['modifyHeadlessConfiguration'] as $_classRef) {
                $_procObj = GeneralUtility::makeInstance($_classRef);
                $_procObj->modifyHeadlessConfiguration($conf, $contentElementUid, $request, $handler);
            }
        }

        $headlessContentElementsUids = $conf['headlessContentElements'] ? explode(',', $conf['headlessContentElements']) : [];
        $allowedRemoteIpMaskList = $conf['headlessAllowedRemoteIpMaskList'] ?? '';
        $fieldsWhitelist = $conf['headlessFieldsWhitelist'] ?? '';

        $ipAddress = $request->getAttribute('normalizedParams')->getRemoteAddress();
        $allowed =
            ($contentElementUid != 0)
            && $this->checkIfContentElementIsAllowed($contentElementUid, $headlessContentElementsUids)
            && $this->checkIfIpIsAllowed($ipAddress, $allowedRemoteIpMaskList);

        if ($allowed) {
            /** @var HeadlessApi $headlessApi */
            $headlessApi = GeneralUtility::makeInstance(HeadlessApi::class);
            $headlessApi->setHeadlessMode(true);
            $responseContent = $headlessApi->renderResponse($contentElementUid);
            $responseContent = $headlessApi->applyFieldWhitelist($responseContent, $fieldsWhitelist);
            $statusCode = 200;
        } else {
            $responseContent = json_encode(['Forbidden']);
            $statusCode = 403;
        }

        $body = new Stream('php://temp', 'rw');
        $body->write($responseContent);
        return (new Response())
            ->withHeader('content-type', 'application/json; charset=utf-8')
            ->withBody($body)
            ->withStatus($statusCode);
    }

    protected function checkIfContentElementIsAllowed($contentElementUid, $headlessContentElementUids)
    {
        return in_array($contentElementUid, $headlessContentElementUids);
    }

    protected function checkIfIpIsAllowed($ipAddress, $headlessAllowedRemoteIpMaskLIst)
    {
        return GeneralUtility::cmpIP($ipAddress, $headlessAllowedRemoteIpMaskLIst);
    }
}
