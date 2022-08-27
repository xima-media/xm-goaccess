<?php

namespace Xima\XmManual\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ManualController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    protected IconFactory $iconFactory;

    protected PageRenderer $pageRenderer;

    protected PageRepository $pageRepository;

    protected SiteFinder $siteFinder;

    protected ?ModuleTemplate $moduleTemplate = null;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        IconFactory $iconFactory,
        PageRenderer $pageRenderer,
        PageRepository $pageRepository,
        SiteFinder $siteFinder
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->iconFactory = $iconFactory;
        $this->pageRenderer = $pageRenderer;
        $this->pageRepository = $pageRepository;
        $this->siteFinder = $siteFinder;
    }

    public function indexAction(): ResponseInterface
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/XmManual/ManualModule');

        $this->view->setTemplateRootPaths(['EXT:xm_manual/Resources/Private/Templates']);
        $this->view->setPartialRootPaths(['EXT:xm_manual/Resources/Private/Partials']);
        $this->view->setLayoutRootPaths(['EXT:xm_manual/Resources/Private/Layouts']);

        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->getLanguageService()->includeLLFile('EXT:xm_manual/Resources/Private/Language/locallang.xlf');
        $this->pageRenderer->addInlineLanguageLabelFile('EXT:xm_manual/Resources/Private/Language/locallang.xlf');
        $pageId = (int)($this->request->getParsedBody()['id'] ?? $this->request->getQueryParams()['id'] ?? 0);

        $this->moduleTemplate->setBodyTag('<body class="typo3-module-xm_manual">');
        $this->moduleTemplate->setModuleId('typo3-module-manual');

        $pageinfo = BackendUtility::readPageAccess(
            $pageId,
            $this->getBackendUser()->getPagePermsClause(Permission::PAGE_SHOW)
        );

        $this->moduleTemplate->setTitle(
            $this->getLanguageService()->sL('LLL:EXT:xm_manual/Resources/Private/Language/locallang_mod.xlf:mlang_tabs_tab'),
            $pageinfo['title'] ?? ''
        );

        $this->moduleTemplate->setContent($this->view->render());
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
