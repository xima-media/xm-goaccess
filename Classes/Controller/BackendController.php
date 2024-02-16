<?php

namespace Xima\XmGoaccess\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmGoaccess\Domain\Model\Dto\Demand;
use Xima\XmGoaccess\Domain\Repository\MappingRepository;
use Xima\XmGoaccess\Service\DataProviderService;

class BackendController extends ActionController
{
    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected ExtensionConfiguration $extensionConfiguration,
        protected MappingRepository $mappingRepository,
        protected IconFactory $iconFactory,
        protected DataProviderService $dataProvider
    ) {
    }

    public function indexAction(): ResponseInterface
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_goaccess');

        if (!isset($extConf['html_path']) || !$extConf['html_path']) {
            $content = 'No "html_path" set';
            return $this->htmlResponse($content);
        }

        $filePath = str_starts_with(
            $extConf['html_path'],
            '/'
        ) ? $extConf['html_path'] : Environment::getPublicPath() . '/' . $extConf['html_path'];
        if (!file_exists($filePath)) {
            $content = 'File "' . $filePath . '" not found';
            return $this->htmlResponse($content);
        }

        $content = file_get_contents($filePath);
        $css = '<style>body{max-height:100vh;}</style>';
        $html = $content . $css;

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->addButtonBar($moduleTemplate);
        $moduleTemplate->setContent($html);
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    public function pathsAction(): ResponseInterface
    {
        $demand = Demand::createFromRequest($this->request);
        $mappings = $this->mappingRepository->findAll();

        $requests = $this->dataProvider->getRequestList($demand);

        GeneralUtility::makeInstance(PageRenderer::class)->loadRequireJsModule('TYPO3/CMS/XmGoaccess/MappingsList');

        $this->view->assign('mappings', $mappings);
        $this->view->assign('requests', $requests);
        $this->view->assign('demand', $demand);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->addButtonBar($moduleTemplate);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    private function addButtonBar(ModuleTemplate $moduleTemplate): void
    {
        $actionName = $this->request->getControllerActionName();

        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $indexButton = $buttonBar->makeLinkButton()
            ->setHref($this->uriBuilder->uriFor('index'))
            ->setTitle('Charts')
            ->setClasses('type-switch ' . ($actionName === 'index' ? 'active' : ''))
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('content-widget-text', Icon::SIZE_SMALL));
        $requestButton = $buttonBar->makeLinkButton()
            ->setHref($this->uriBuilder->uriFor('paths'))
            ->setTitle('Requests')
            ->setClasses('type-switch ' . ($actionName === 'paths' ? 'active' : ''))
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('content-widget-text', Icon::SIZE_SMALL));
        $mappingsButton = $buttonBar->makeLinkButton()
            ->setHref($this->uriBuilder->uriFor('mappings'))
            ->setTitle('Mappings')
            ->setClasses('type-switch ' . ($actionName === 'mappings' ? 'active' : ''))
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('apps-pagetree-page-shortcut-external', Icon::SIZE_SMALL));
        $buttonBar->addButton($requestButton, ButtonBar::BUTTON_POSITION_LEFT, 1);
        $buttonBar->addButton($mappingsButton, ButtonBar::BUTTON_POSITION_LEFT, 1);
    }

    public function pageChartAction(ServerRequestInterface $request): ResponseInterface
    {
        $pid = $request->getQueryParams()['pid'] ?? 0;

        if (!$pid) {
            return new JsonResponse([], 404);
        }

        $chartData = $this->dataProvider->getPageChartData((int)$pid);

        return new JsonResponse($chartData);
    }

    public function userSettingsAction(ServerRequestInterface $request): ResponseInterface
    {
        $pageHeaderChart = $request->getParsedBody()['pageHeaderChart'] ?? false;
        $pageHeaderChartSettings = filter_var($pageHeaderChart, FILTER_VALIDATE_BOOLEAN);

        $GLOBALS['BE_USER']->pushModuleData('goaccess_settings', ['pageHeaderChart' => $pageHeaderChartSettings]);

        return new JsonResponse([]);
    }

    public function mappingsAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->addButtonBar($moduleTemplate);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
