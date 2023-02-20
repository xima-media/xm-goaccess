<?php

namespace Xima\XmDkfzNetEvents\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Cache\CacheManager;

class CacheEventsCommand extends Command
{
    public function __construct(protected CacheManager $cacheManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Clears the cache of pages that display DKFZ RSS events');
        $this->setHelp('');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cacheManager->flushCachesByTag('dkfz_jobs');

        return Command::SUCCESS;
    }
}
