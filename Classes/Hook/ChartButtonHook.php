<?php

namespace Xima\XmGoaccess\Hook;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use Xima\XmGoaccess\Domain\Repository\RequestRepository;

final class ChartButtonHook
{
    public function __construct(
        private readonly RequestRepository $requestRepository,
        private readonly IconFactory $iconFactory,
        private readonly PageRenderer $pageRenderer
    ) {
    }

    public function addChartButton($params, ButtonBar $buttonBar): mixed
    {
        $buttons = $params['buttons'];
        if (GeneralUtility::_GP('route') !== '/module/web/layout') {
            return $buttons;
        }

        $pid = GeneralUtility::_GET('id');
        if (!$pid || !MathUtility::canBeInterpretedAsInteger($pid)) {
            return $buttons;
        }

        $data = $this->requestRepository->getChartDataForPage((int)$pid);
        if (empty($data)) {
            return $buttons;
        }

        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/XmGoaccess/PageHeaderChart');

        $button = $buttonBar->makeLinkButton()
            ->setClasses('goaccess-button')
            ->setDataAttributes([])
            ->setHref('#')
            ->setIcon($this->iconFactory->getIcon('chart', Icon::SIZE_SMALL))
            ->setTitle('Chart');
        $buttons[ButtonBar::BUTTON_POSITION_RIGHT][5][] = $button;

        return $buttons;
    }
}
