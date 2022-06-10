<?php

namespace Xima\XmDkfzNetJobs\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class JobController extends ActionController
{

    public function latestAction(): ResponseInterface
    {
        return $this->htmlResponse('hello');
    }
}
