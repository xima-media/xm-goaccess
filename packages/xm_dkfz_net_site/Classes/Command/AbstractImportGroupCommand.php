<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableGroupInterface;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

abstract class AbstractImportGroupCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected PhoneBookCompareResult $compareResult;

    protected PhoneBookUtility $phoneBookUtility;

    /**
     * @var ImportableGroupInterface
     */
    protected ImportableGroupInterface $groupRepository;

    public function __construct(
        ImportableGroupInterface $groupRepository,
        PhoneBookUtility $phoneBookUtility,
        string $name = null
    ) {
        parent::__construct($name);
        $this->groupRepository = $groupRepository;
        $this->phoneBookUtility = $phoneBookUtility;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Reading Groups from database and JSON..');

        $this->phoneBookUtility->loadJson();

        $apiGroups = $this->phoneBookUtility->getGroupIdentifierInJson();
        $dbGroups = $this->groupRepository->findAllGroupsWithDkfzNumber();

        $io->listing([
            '<success>' . count($apiGroups) . '</success> found in PhoneBook (JSON)',
            '<success>' . count($dbGroups) . '</success> found in database',
        ]);

        $io->writeln('Comparing Groups..');
        $this->compareResult = $this->phoneBookUtility->compareDbGroupsWithJson($dbGroups);

        $io->listing([
            '<success>' . count($this->compareResult->dkfzNumbersToCreate) . '</success> to create',
            '<error>' . count($this->compareResult->dkfzNumbersToDelete) . '</error> to delete',
        ]);

        if (count($this->compareResult->dkfzNumbersToCreate)) {
            $io->write('Creating groups..');
            $pid = $this->phoneBookUtility->getUserStoragePid($this);
            $subgroup = $this->phoneBookUtility->getSubGroupForGroups($this);
            $this->groupRepository->bulkInsertDkfzNumbers($this->compareResult->dkfzNumbersToCreate, $pid, $subgroup);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($this->compareResult->dkfzNumbersToDelete)) {
            $io->write('Deleting groups..');
            $this->groupRepository->deleteByDkfzNumbers($this->compareResult->dkfzNumbersToDelete);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        $io->success('Done');

        return Command::SUCCESS;
    }
}
