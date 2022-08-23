<?php

namespace Xima\XmManual\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ManualController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        return new HtmlResponse('<h1>Hello World</h1>');
    }
}
