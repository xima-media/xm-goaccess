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

    public function crawlJobs(): bool
    {
        // download data
        $jsonJobs = file_get_contents(self::API_URL);

        if (!$jsonJobs) {
            return false;
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
