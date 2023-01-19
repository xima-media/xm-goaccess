<?php

namespace Xima\XmDkfzNetJobs\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmDkfzNetJobs\Utility\JobLoaderUtility;

class JobController extends ActionController
{

    public function __construct(
        protected JobLoaderUtility $jobLoaderUtility
    ) {
    }

    public function latestAction(): ResponseInterface
    {
        $jobs = $this->jobLoaderUtility->getJobs();
        $this->addCacheTag();

        if ($this->settings['latestJobCount'] && MathUtility::canBeInterpretedAsInteger($this->settings['latestJobCount'])) {
            $jobs = array_slice($jobs, 0, (int)$this->settings['latestJobCount']);
        }

        $this->view->assign('jobs', $jobs);

        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $jobs = $this->jobLoaderUtility->getJobs();

        $this->addCacheTag();

        $this->view->assign('jobs', $jobs);

        return $this->htmlResponse();
    }

    public function searchAction(): ResponseInterface
    {
        $categories = $this->jobLoaderUtility->getJobCategories();
        $places = $this->jobLoaderUtility->getJobPlaces();
        $header = $this->configurationManager->getContentObject()?->data['header'] ?? '';

        $this->view->assign('categories', $categories);
        $this->view->assign('places', $places);
        $this->view->assign('header', $header);

        return $this->htmlResponse();
    }

    protected function addCacheTag(): void
    {
        // Add cache tag
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            static $cacheTagsSet = false;
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $typoScriptFrontendController->addCacheTags(['dkfz_jobs']);
                $cacheTagsSet = true;
            }
        }
    }
}
