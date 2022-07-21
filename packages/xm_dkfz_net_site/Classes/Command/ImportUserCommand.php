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

        $phoneBookUtility = GeneralUtility::makeInstance(PhoneBookUtility::class);
        $xpath = $phoneBookUtility->getXpath();

        $xmlUsers = $xpath->query('//x:CPerson[x:AdAccountName[text()!=""]]');
        $dbUsers = $this->userRepository->findAllDkfzUser();

        $io->writeln('Reading Users from database and XML..');

        $io->listing([
            '<success>' . count($xmlUsers) . '</success> found in XML',
            '<success>' . count($dbUsers) . '</success> found in database',
        ]);
        $io->writeln('Comparing Users..');

        $progress = $io->createProgressBar(count($dbUsers));
        $progress->setFormat('%current%/%max% [%bar%] %percent%%');

        $userIdsForActions = ['create' => [], 'update' => [], 'delete' => [], 'skip' => []];
        $phoneBookUsersById = [];

        foreach ($dbUsers ?? [] as $dbUser) {
            $progress->advance();
            $userNode = $xpath->query('//x:CPerson[x:Id[text()="' . $dbUser['dkfz_id'] . '"]]');

            // search for user id in xml and mark for update if changed (as skipped otherwise)
            if ($userNode->length === 1) {
                $nodeHash = md5($userNode->item(0)->nodeValue);
                if ($dbUser['dkfz_hash'] !== $nodeHash) {
                    $userIdsForActions['update'][] = $dbUser['dkfz_id'];
                    $phoneBookUsersById[$dbUser['dkfz_id']] = PhoneBookPerson::createFromXpathNode(
                        $xpath,
                        $userNode->item(0)
                    );
                } else {
                    $userIdsForActions['skip'][] = $dbUser['dkfz_id'];
                }
                continue;
            }

            // delete user from database if not found in xml
            $userIdsForActions['delete'][] = $dbUser['dkfz_id'];
        }

        foreach ($xmlUsers as $xmlUserNode) {
            $userId = $xpath->query('x:Id', $xmlUserNode)->item(0)->nodeValue;

            // skip creation if already marked to update or to skip
            if (in_array($userId, array_merge($userIdsForActions['update'], $userIdsForActions['skip']), true)) {
                continue;
            }

            $userIdsForActions['create'][] = $userId;
            $phoneBookUsersById[$userId] = PhoneBookPerson::createFromXpathNode($xpath, $xmlUserNode);
        }

        $progress->finish();
        $io->newLine();

        $io->listing([
            '<success>' . count($userIdsForActions['create']) . '</success> to create',
            '<warning>' . count($userIdsForActions['update']) . '</warning> to update',
            '<error>' . count($userIdsForActions['delete']) . '</error> to delete',
            '' . count($userIdsForActions['skip']) . ' to skip',
        ]);

        $phoneBookUsersToCreate = array_filter(
            $phoneBookUsersById,
            function ($id) use ($userIdsForActions) {
                return in_array($id, $userIdsForActions['create']);
            },
            ARRAY_FILTER_USE_KEY
        );
        if (count($phoneBookUsersToCreate)) {
            $io->writeln('Creating users..');
            $this->userRepository->bulkInsertFromPhoneBook($phoneBookUsersToCreate);
        }

        $phoneBookUsersToUpdate = array_filter(
            $phoneBookUsersById,
            function ($id) use ($userIdsForActions) {
                return in_array($id, $userIdsForActions['update']);
            },
            ARRAY_FILTER_USE_KEY
        );
        foreach ($phoneBookUsersToUpdate ?? [] as $phoneBookPerson) {
            $this->userRepository->updateFromPhoneBook($phoneBookPerson);
        }

        if (count($userIdsForActions['delete'])) {
            $io->writeln('Deleting users..');
            $this->userRepository->deleteByDkfzIds($userIdsForActions['delete']);
        }

        return Command::SUCCESS;
    }
}
