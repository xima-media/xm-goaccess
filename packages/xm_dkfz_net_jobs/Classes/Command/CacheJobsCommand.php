<?php

namespace Xima\XmDkfzNetJobs\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xima\XmDkfzNetJobs\Utility\JobLoaderUtility;

class CacheJobsCommand extends Command
{
    protected JobLoaderUtility $jobLoaderUtility;

    public function __construct(JobLoaderUtility $jobLoaderUtility, string $name = null)
    {
        parent::__construct($name);
        $this->jobLoaderUtility = $jobLoaderUtility;
    }

    protected function configure(): void
    {
        $this->setDescription('Downloads and caches job postings from jobs.dkfz.de');
        $this->setHelp('');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $success = $this->jobLoaderUtility->loadJobs(false);

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
