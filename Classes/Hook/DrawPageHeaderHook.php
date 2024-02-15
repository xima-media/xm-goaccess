<?php

namespace Xima\XmGoaccess\Hook;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
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

        $requests = $this->requestRepository->getChartDataForPage($pageInfo['uid']);

        if (!count($requests)) {
            return '';
        }

        return '<div class="dashboard-item hidden" style="width: 800px;"><canvas data-page-uid="' . $pageInfo['uid'] . '"></canvas></div>';
    }
}
