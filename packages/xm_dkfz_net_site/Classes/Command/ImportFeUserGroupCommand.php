<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;
use Xima\XmDkfzNetSite\Domain\Model\User;
use Xima\XmDkfzNetSite\Domain\Repository\UserGroupRepository;
use Xima\XmDkfzNetSite\Domain\Repository\UserRepository;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

class ImportFeUserGroupCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected UserGroupRepository $groupRepository;

    public function __construct(
        UserGroupRepository $userRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->groupRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding fe_groups');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Reading Groups from database and XML..');

        $phoneBookUtility = GeneralUtility::makeInstance(PhoneBookUtility::class);
        $phoneBookUtility->loadXpath();

        $xmlGroups = $phoneBookUtility->getGroupIdentifierInXml();
        $dbGroups = $this->groupRepository->findAllGroupsWithDkfzId();

        $io->listing([
            '<success>' . count($xmlGroups) . '</success> found in XML',
            '<success>' . count($dbGroups) . '</success> found in database',
        ]);

        $io->writeln('Comparing Groups..');
        $progress = $io->createProgressBar(count($dbGroups));
        $progress->setFormat('%current%/%max% [%bar%] %percent%%');
        $compareResult = $phoneBookUtility->compareFeGroupsWithXml($dbGroups, $progress);
        $progress->finish();
        $io->newLine(2);

        $io->listing([
            '<success>' . count($compareResult->dkfzIdsToCreate) . '</success> to create',
            '<error>' . count($compareResult->dkfzIdsToDelete) . '</error> to delete',
        ]);

        if (count($compareResult->dkfzIdsToCreate)) {
            $io->write('Creating users..');
            $pid = $phoneBookUtility->getUserStoragePid();
            $this->groupRepository->bulkInsertDkfzIds($compareResult->dkfzIdsToCreate, $pid);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($compareResult->dkfzIdsToDelete)) {
            $io->write('Deleting users..');
            $this->groupRepository->deleteByDkfzIds($compareResult->dkfzIdsToDelete);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        $io->success('Done');

        return Command::SUCCESS;
    }
}
