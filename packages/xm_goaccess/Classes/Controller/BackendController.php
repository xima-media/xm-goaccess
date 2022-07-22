<?php

namespace Xima\XmGoaccess\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BackendController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    protected ExtensionConfiguration $extensionConfiguration;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        ExtensionConfiguration $extensionConfiguration
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function indexAction(): ResponseInterface
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_goaccess');

        if (!isset($extConf['html_path']) || !$extConf['html_path']) {
            $content = 'No "html_path" set';
            return $this->htmlResponse($content);
        }

        $filePath = Environment::getPublicPath() . '/' . $extConf['html_path'];
        if (!file_exists($filePath)) {
            $content = 'File "' . $filePath . '" not found';
            return $this->htmlResponse($content);
        }

        $content = file_get_contents($filePath);
        $css = '<style>body{max-height:100vh;}</style>';
        return $this->htmlResponse($content . $css);
    }
}
