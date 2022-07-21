<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;
use Xima\XmDkfzNetSite\Domain\Model\User;
use Xima\XmDkfzNetSite\Domain\Repository\UserRepository;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

class ImportUserCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding fe_users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Reading Users from database and XML..');

        $phoneBookUtility = GeneralUtility::makeInstance(PhoneBookUtility::class);
        $phoneBookUtility->loadXpath();

        $xmlUsers = $phoneBookUtility->getUsersInXml();
        $dbUsers = $this->userRepository->findAllDkfzUser();

        $io->listing([
            '<success>' . count($xmlUsers) . '</success> found in XML',
            '<success>' . count($dbUsers) . '</success> found in database',
        ]);

        $io->writeln('Comparing Users..');
        $progress = $io->createProgressBar(count($dbUsers));
        $progress->setFormat('%current%/%max% [%bar%] %percent%%');
        $compareResult = $phoneBookUtility->compareFeUserWithXml($dbUsers, $progress);
        $progress->finish();
        $io->newLine(2);

        $io->listing([
            '<success>' . count($compareResult->dkfzIdsToCreate) . '</success> to create',
            '<warning>' . count($compareResult->dkfzIdsToUpdate) . '</warning> to update',
            '<error>' . count($compareResult->dkfzIdsToDelete) . '</error> to delete',
            '' . count($compareResult->dkfzIdsToSkip) . ' to skip',
        ]);

        if (count($compareResult->dkfzIdsToCreate)) {
            $io->write('Creating users..');
            $phoneBookUsersToAdd = $compareResult->getPhoneBookPersonsForAction('create');
            $this->userRepository->bulkInsertFromPhoneBook($phoneBookUsersToAdd);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($compareResult->dkfzIdsToUpdate)) {
            $io->write('Updating users..');
            $phoneBookUsersToUpdate = $compareResult->getPhoneBookPersonsForAction('update');
            foreach ($phoneBookUsersToUpdate ?? [] as $phoneBookPerson) {
                $this->userRepository->updateFromPhoneBook($phoneBookPerson);
            }
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($compareResult->dkfzIdsToDelete)) {
            $io->write('Deleting users..');
            $this->userRepository->deleteByDkfzIds($compareResult->dkfzIdsToDelete);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        $io->success('Done');

        return Command::SUCCESS;
    }
}
