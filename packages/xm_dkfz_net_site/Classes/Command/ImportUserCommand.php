<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Xima\XmDkfzNetSite\Domain\Model\User;
use Xima\XmDkfzNetSite\Domain\Repository\UserRepository;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

class ImportUserCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected UserRepository $userRepository;

    protected PersistenceManager $persistenceManager;

    public function __construct(
        UserRepository $userRepository,
        PersistenceManager $persistenceManager,
        string $name = null
    ) {
        parent::__construct($name);
        $this->persistenceManager = $persistenceManager;
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

        $phoneBookUtility = GeneralUtility::makeInstance(PhoneBookUtility::class);
        $xpath = $phoneBookUtility->getXpath();

        $xmlUsers = $xpath->query('x:CPerson');
        $dbUsers = $this->userRepository->findAll();

        $io->writeln('Reading Users from database and XML..');

        $io->listing([
            '<success>' . count($xmlUsers) . '</success> found in XML',
            '<success>' . $dbUsers->count() . '</success> found in database',
        ]);

        $io->writeln('Comparing Users..');

        $userIdsToUpdate = [];
        $userActions = ['create' => [], 'update' => [], 'delete' => []];

        /** @var \Xima\XmDkfzNetSite\Domain\Model\User $dbUser */
        foreach ($dbUsers ?? [] as $dbUser) {
            $dkfzUserId = $dbUser->getDkfzId();

            $xmlUser = $xpath->query('//CPerson[/Id[text()="' . $dkfzUserId . '"]]');

            // search for user id in xml and mark for update
            if ($xpath->query('//CPerson/Id[text()="' . $dkfzUserId . '"]')->length) {
                $userActions['update'][] = $dbUser;
                $userIdsToUpdate[] = $dbUser;
                continue;
            }

            // delete user from database if not found in xml
            $userActions['delete'][] = $dbUser;
        }

        $xmlUsersIds = $xpath->query('//x:CPerson/x:Id');
        foreach ($xmlUsersIds as $objNode) {
            $userId = (string)$objNode->nodeValue;

            // skip creation if already marked to update
            if (in_array($userId, $userIdsToUpdate, true)) {
                continue;
            }

            $newUser = new User();
            $newUser->setDkfzId($userId);
            $userActions['create'][] = $newUser;
        }

        $io->listing([
            '<success>' . count($userActions['create']) . '</success> to create',
            '<warning>' . count($userActions['update']) . '</warning> to update',
            '<error>' . count($userActions['delete']) . '</error> to delete',
        ]);

        return Command::SUCCESS;
    }
}
