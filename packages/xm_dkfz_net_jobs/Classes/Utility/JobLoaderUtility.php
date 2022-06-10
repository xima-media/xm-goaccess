<?php

namespace Xima\XmDkfzNetJobs\Utility;

use JsonMapper;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use Xima\XmDkfzNetJobs\Domain\Model\Dto\Job;

class JobLoaderUtility
{
    protected FrontendInterface $cache;

    protected const API_URL = 'https://jobs.dkfz.de/jobPublication/list.json?language=de';

    /**
     * @var Job[]
     */
    protected array $jobs = [];

    public function __construct(FrontendInterface $cache)
    {
        $this->cache = $cache;
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

    protected function loadJobs($useCache = true): bool
    {
        // download and cache json
        if (!($jsonJobs = $this->cache->get('dkfz')) && $useCache) {

            $jsonJobs = file_get_contents(self::API_URL);

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
                $jsonJobs, array(), Job::class
            );
        } catch (\JsonMapper_Exception) {
            return false;
        }

        return true;
    }

}
