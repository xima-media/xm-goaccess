<?php

namespace Xima\XmDkfzNetJobs\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmDkfzNetJobs\Utility\JobLoaderUtility;

class JobController extends ActionController
{
    protected JobLoaderUtility $jobLoaderUtility;

    public function __construct(JobLoaderUtility $jobLoaderUtility)
    {
        $this->jobLoaderUtility = $jobLoaderUtility;
    }

    public function latestAction(): ResponseInterface
    {
        $jobs = $this->jobLoaderUtility->getJobs();

        if ($this->settings['latestJobCount'] && MathUtility::canBeInterpretedAsInteger($this->settings['latestJobCount'])) {
            $jobs = array_slice($jobs, 0, (int)$this->settings['latestJobCount']);
        }

        $this->view->assign('jobs', $jobs);

        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $jobs = $this->jobLoaderUtility->getJobs();

        $this->view->assign('jobs', $jobs);

        return $this->htmlResponse();
    }
}
