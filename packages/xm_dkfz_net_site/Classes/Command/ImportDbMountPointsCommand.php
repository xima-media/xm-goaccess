<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Repository\BeGroupRepository;

class ImportDbMountPointsCommand extends Command
{
    public function __construct(
        protected BeGroupRepository $beGroupRepository,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Reading backend groups from database');


        $groups = $this->beGroupRepository->findAllGroupsWithoutShortNewsMountPoint();

        \TYPO3\CMS\Core\Utility\DebugUtility::debug(count($groups), 'Debug: ' . __FILE__ . ' in Line: ' . __LINE__);

        return Command::SUCCESS;
    }
}
