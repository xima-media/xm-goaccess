<?php

namespace Xima\XmDkfzNetSite\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ResetImportHashCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected LoggerInterface $logger;

    protected function configure(): void
    {
        $this->setDescription('Removes the dkfz_hash for user and groups');
        $this->setHelp('Call this command to do a full update of all users, regardless of changes in the phonebook.json at the next run');
    }

    public function __construct(
        LoggerInterface $logger,
        string $name = null
    ) {
        parent::__construct($name);
        $this->logger = $logger;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $tables = ['fe_users', 'be_users', 'fe_groups', 'be_groups', 'tx_xmdkfznetsite_domain_model_place'];
        foreach ($tables as $table) {
            $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
            $qb->update($table)
                ->set('dkfz_hash', '')
                ->execute();
        }

        return Command::SUCCESS;
    }
}
