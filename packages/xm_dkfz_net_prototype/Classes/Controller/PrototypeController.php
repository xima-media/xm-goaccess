<?php

namespace Xima\XmDkfzNetPrototype\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Controller\AboutController;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PrototypeController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        $path = PathUtility::getPublicResourceWebPath('EXT:xm_dkfz_net_prototype/Resources/Public/patternlab/index.html');

        if (!str_contains($path, 'index.html')) {
            return $this->htmlResponse('Pattern Lab was not found. Have you run "npm run build:patternlab"?');
        }

        return new RedirectResponse($path);
    }
}
