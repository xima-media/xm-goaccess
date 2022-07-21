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

        $phoneBookUsersById = $compareResult['phoneBookUsersById'];
        $usersIdToCreate = $compareResult['actions']['create'];
        $usersIdToUpdate = $compareResult['actions']['update'];
        $usersIdToDelete = $compareResult['actions']['delete'];
        $usersIdToSkip = $compareResult['actions']['skip'];

        $io->listing([
            '<success>' . count($usersIdToCreate) . '</success> to create',
            '<warning>' . count($usersIdToUpdate) . '</warning> to update',
            '<error>' . count($usersIdToDelete) . '</error> to delete',
            '' . count($usersIdToSkip) . ' to skip',
        ]);

        $phoneBookUsersToCreate = array_filter(
            $phoneBookUsersById,
            function ($id) use ($usersIdToCreate) {
                return in_array($id, $usersIdToCreate);
            },
            ARRAY_FILTER_USE_KEY
        );
        if (count($phoneBookUsersToCreate)) {
            $io->writeln('Creating users..');
            $this->userRepository->bulkInsertFromPhoneBook($phoneBookUsersToCreate);
            $io->write('<success>done</success>');
        }

        $phoneBookUsersToUpdate = array_filter(
            $phoneBookUsersById,
            function ($id) use ($usersIdToUpdate) {
                return in_array($id, $usersIdToUpdate);
            },
            ARRAY_FILTER_USE_KEY
        );
        foreach ($phoneBookUsersToUpdate ?? [] as $phoneBookPerson) {
            $io->writeln('Updating users..');
            $this->userRepository->updateFromPhoneBook($phoneBookPerson);
            $io->write('<success>done</success>');
        }

        if (count($usersIdToDelete)) {
            $io->writeln('Deleting users..');
            $this->userRepository->deleteByDkfzIds($usersIdToDelete);
            $io->write('<success>done</success>');
        }

        return Command::SUCCESS;
    }
}
