<?php

namespace Xima\XmGoaccess\Hook;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmGoaccess\Domain\Repository\RequestRepository;

class DrawPageHeaderHook
{
    public function __construct(protected RequestRepository $requestRepository)
    {
    }

    /**
     * @param array<int, mixed> $configuration
     * @param PageLayoutController $parentObject
     * @return string
     */
    public function addPageChart(array $configuration, PageLayoutController $parentObject): string
    {
        $pageInfo = $parentObject->pageinfo;

        if (!is_array($pageInfo)) {
            return '';
        }

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/XmGoaccess/PageHeaderChart');

        $requests = $this->requestRepository->getChartDataForPage($pageInfo['uid']);

        if (!count($requests)) {
            return '';
        }

        $settings = $GLOBALS['BE_USER']->getModuleData('goaccess_settings') ?? [];
        $onload = isset($settings['pageHeaderChart']) && $settings['pageHeaderChart'] ? 1 : 0;
        $style = $onload ? '' : 'display:none';

        return '<div class="dashboard-item" style="width: 500px;"><canvas style="' . $style . '" data-onload="' . $onload . '" data-page-uid="' . $pageInfo['uid'] . '"></canvas></div>';
    }
}
