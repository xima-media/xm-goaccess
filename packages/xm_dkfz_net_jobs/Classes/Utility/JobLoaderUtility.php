<?php

namespace Xima\XmDkfzNetJobs\Utility;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonMapper;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Xima\XmDkfzNetJobs\Domain\Model\Dto\Job;

class JobLoaderUtility
{
    protected FrontendInterface $cache;

    protected ExtensionConfiguration $extensionConfiguration;

    private LoggerInterface $logger;

    /**
     * @var Job[]
     */
    protected array $jobs = [];

    public function __construct(
        FrontendInterface $cache,
        ExtensionConfiguration $extensionConfiguration,
        LoggerInterface $logger
    ) {
        $this->cache = $cache;
        $this->extensionConfiguration = $extensionConfiguration;
        $this->logger = $logger;
    }

    public function updateJobs(): bool
    {
        return $this->loadJobs(false);
    }

    /**
     * @return \Xima\XmDkfzNetJobs\Domain\Model\Dto\Job[]
     */
    public function getJobs(): array
    {
        $this->loadJobs();

        return $this->jobs;
    }

    protected function loadJobs(bool $useCache = true): bool
    {
        // download and cache json
        if (!($jsonJobs = $this->cache->get('dkfz')) || !$useCache) {
            $jsonJobs = $this->fetchRemoteJobs();
            $this->cache->set('dkfz', $jsonJobs);
        }

        if (!is_string($jsonJobs)) {
            $this->logger->error('Cached jobs are not valid', ['code' => 1658818320, 'jobs' => $jsonJobs]);
            return false;
        }

        $jobsArray = $this->decodeJsonJobs($jsonJobs);

        $this->orderJobsArray($jobsArray);

        $this->jobs = $this->mapJobsArray($jobsArray);

        return true;
    }

    /**
     * @param Job[] $jobsArray
     */
    protected function orderJobsArray(array &$jobsArray): void
    {
        usort($jobsArray, function ($a, $b) {
            if (!$a->startDate || !$b->startDate || $a->startDate === $b->startDate) {
                return 0;
            }

            return $a->startDate > $b->startDate ? -1 : 1;
        });
    }

    protected function fetchRemoteJobs(): string
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_jobs');

        if (!isset($extConf['api_url']) || !$extConf['api_url'] || !is_string($extConf['api_url'])) {
            $this->logger->error('Api url not set', ['url' => $extConf['api_url'], 'code' => 1658818450]);
            return '';
        }

        try {
            $client = new Client(['verify' => false]);
            $response = $client->request('GET', $extConf['api_url']);
            $jsonJobs = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            $this->logger->error('Could not fetch job postings', ['error' => $e->getMessage(), 'code' => 1658818306]);
            return '';
        }

        if (!$jsonJobs) {
            $this->logger->error('Fetched jobs response is empty', ['code' => 1658818310]);
            return '';
        }

        return $jsonJobs;
    }

    /**
     * @param string $jsonJobs
     * @return array<mixed>
     */
    protected function decodeJsonJobs(string $jsonJobs): array
    {
        // decode string
        $jsonJobs = json_decode($jsonJobs);

        if (!is_array($jsonJobs)) {
            $this->logger->error('Decoded jobs are not valid', ['code' => 1658818330, 'jobs' => $jsonJobs]);
            return [];
        }

        return $jsonJobs;
    }

    /**
     * @param array<mixed> $jobsArray
     * @return array<Job>
     */
    protected function mapJobsArray(array $jobsArray): array
    {
        $mapper = new JsonMapper();

        // map JSON to DTO
        try {
            $jobs = $mapper->mapArray(
                $jobsArray,
                [],
                Job::class
            );
        } catch (\JsonMapper_Exception $e) {
            $this->logger->error('Could not map json to Jobs', ['code' => 1658818340, 'error' => $e]);
            return [];
        }

        if (!is_array($jobs)) {
            $this->logger->error('Mapped jobs are not valid', ['code' => 1658818350, 'jobs' => $jobs]);
            return [];
        }

        return $jobs;
    }
}
