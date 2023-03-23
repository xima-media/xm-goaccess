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

        $filePath = str_starts_with($extConf['html_path'],
            '/') ? $extConf['html_path'] : Environment::getPublicPath() . '/' . $extConf['html_path'];
        if (!file_exists($filePath)) {
            $content = 'File "' . $filePath . '" not found';
            return $this->htmlResponse($content);
        }

        $content = file_get_contents($filePath);
        $css = '<style>body{max-height:100vh;}</style>';
        return $this->htmlResponse($content . $css);
    }

    public function mappingsAction(): ResponseInterface
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
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $beUser = $buttonBar->makeLinkButton()
            ->setHref('#request')
            ->setTitle('Requests')
            ->setClasses('active type-switch')
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('content-widget-text', Icon::SIZE_SMALL));
        $feUser = $buttonBar->makeLinkButton()
            ->setHref('#mappings')
            ->setTitle('Mappings')
            ->setClasses('type-switch')
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('apps-pagetree-page-shortcut-external', Icon::SIZE_SMALL));
        $buttonBar->addButton($beUser, ButtonBar::BUTTON_POSITION_LEFT, 1);
        $buttonBar->addButton($feUser, ButtonBar::BUTTON_POSITION_LEFT, 1);
    }

    public function pageChartAction(ServerRequestInterface $request): ResponseInterface
    {
        $pid = (int)$request->getQueryParams()['pid'] ?? 0;

        if (!$pid) {
            return new JsonResponse([], 404);
        }

        $chartData = $this->dataProvider->getPageChartData($pid);

        return new JsonResponse($chartData);
    }
}
