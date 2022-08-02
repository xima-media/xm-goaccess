<?php

namespace Xima\XmDkfzNetSite\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Console\CommandRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportAllCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected LoggerInterface $logger;

    protected function configure(): void
    {
        $this->setDescription('Runs all DKFZ importer');
        $this->setHelp('Reads all user, user groups, places and contacts from API and imports them');
    }

    public function __construct(
        LoggerInterface $logger,
        string $name = null
    ) {
        parent::__construct($name);
        $this->logger = $logger;
    }

    /**
     * @throws \TYPO3\CMS\Core\Console\UnknownCommandException
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        $commandRegistry = GeneralUtility::makeInstance(CommandRegistry::class);

        $output = $input->getOption('verbose') ? $output : new BufferedOutput();

        $commandsToRun = ['dkfz:importFeUserGroup', 'dkfz:importBeUserGroup', 'dkfz:importFeUser', 'dkfz:importPlace'];

        foreach ($commandsToRun as $commandName) {
            $updateImmoCmd = $application ? $application->find($commandName) : $commandRegistry->getCommandByIdentifier($commandName);
            $updateImmoCmd->run($input, $output);

            if ($output instanceof BufferedOutput) {
                $this->logger->info($output->fetch());
            }
        }

        return Command::SUCCESS;
    }
}
