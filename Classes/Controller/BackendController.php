<?php

namespace Xima\XmGoaccess\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmGoaccess\Domain\Repository\MappingRepository;

class BackendController extends ActionController
{
    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected ExtensionConfiguration $extensionConfiguration,
        protected MappingRepository $mappingRepository
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
        $mappings = $this->mappingRepository->findAll();

        $this->view->assign('mappings', $mappings);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        $buttonbar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // Adding title, menus, buttons, etc. using $moduleTemplate ...
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
