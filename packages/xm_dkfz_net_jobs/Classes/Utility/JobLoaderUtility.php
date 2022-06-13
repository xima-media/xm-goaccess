<?php

namespace Xima\XmDkfzNetJobs\Utility;

use JsonMapper;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Xima\XmDkfzNetJobs\Domain\Model\Dto\Job;

class JobLoaderUtility
{
    protected FrontendInterface $cache;

    protected ExtensionConfiguration $extensionConfiguration;

    /**
     * @var Job[]
     */
    protected array $jobs = [];

    public function __construct(FrontendInterface $cache, ExtensionConfiguration $extensionConfiguration)
    {
        $this->cache = $cache;
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function updateJobs(): bool
    {
        return $this->loadJobs(false);
    }

    public function getJobs(): array
    {
        $this->loadJobs();

        return $this->jobs;
    }

    /**
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     */
    protected function loadJobs($useCache = true): bool
    {
        // download and cache json
        if (!($jsonJobs = $this->cache->get('dkfz')) && $useCache) {
            $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_jobs');

            if (!isset($extConf['api_url']) || !$extConf['api_url']) {
                return false;
            }

            $jsonJobs = file_get_contents($extConf['api_url']);

            if (!$jsonJobs) {
                return false;
            }

            $this->cache->set('dkfz', $jsonJobs);
        }

        // decode string
        $jsonJobs = json_decode($jsonJobs);
        $mapper = new JsonMapper();

        // map JSON to DTO
        try {
            $this->jobs = $mapper->mapArray(
                $jsonJobs,
                [],
                Job::class
            );
        } catch (\JsonMapper_Exception) {
            return false;
        }

        return true;
    }
}
