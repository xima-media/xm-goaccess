<?php

namespace Xima\XmManual\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Tree\Repository\PageTreeRepository;
use TYPO3\CMS\Core\Database\Query\Restriction\DocumentTypeExclusionRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmManual\Database\Query\Restriction\DocumentTypeExclusiveRestriction;

class TreeController extends \TYPO3\CMS\Backend\Controller\Page\TreeController
{
    protected ServerRequestInterface $request;

    public function fetchDataAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return parent::fetchDataAction($request);
    }

    protected function getPageTreeRepository(): PageTreeRepository
    {
        /** @var \TYPO3\CMS\Core\Http\NormalizedParams $params */
        $params = $this->request->getAttribute('normalizedParams');
        $referer = $params->getHttpReferer();

        if (str_contains($referer, 'help/XmManual')) {
            return $this->getPageTreeRepositoryForManualModule();
        }

        return parent::getPageTreeRepository();
    }

    protected function getPageTreeRepositoryForManualModule(): PageTreeRepository
    {
        $backendUser = $this->getBackendUser();
        $exclusiveDocumentTypes = [701];

        $additionalQueryRestrictions[] = GeneralUtility::makeInstance(
            DocumentTypeExclusiveRestriction::class,
            $exclusiveDocumentTypes
        );

        return GeneralUtility::makeInstance(
            PageTreeRepository::class,
            (int)$backendUser->workspace,
            [],
            $additionalQueryRestrictions
        );
    }
}
