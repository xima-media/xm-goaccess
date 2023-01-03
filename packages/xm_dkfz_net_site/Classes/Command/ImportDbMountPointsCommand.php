<?php

namespace Xima\XmDkfzNetSite\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use Xima\XmDkfzNetSite\Domain\Repository\BeGroupRepository;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

class ImportDbMountPointsCommand extends Command
{
    public function __construct(
        protected BeGroupRepository $beGroupRepository,
        protected PhoneBookUtility $phoneBookUtility,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Update db_mountpoints of be_groups');
        $this->setHelp('Creates defined mount points (e.g. sys_folder) for backend groups');
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Create missing short news page records..');
        $createdFolders = $this->createShortNewsMountPoints();
        $io->newLine(1);
        $message = $createdFolders ? ['Created <success>' . $createdFolders . '</success> records'] : ['No records to add'];
        $io->listing($message);

        $io->success('Done');
        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function createShortNewsMountPoints(): int
    {
        $shortNewsStorageUid = $this->getShortNewsStorageUid();
        $groups = $this->beGroupRepository->findAllGroupsWithoutShortNewsMountPoint();

        if (!count($groups)) {
            return 0;
        }

        $data = [];

        foreach ($groups as $key => $group) {
            $data['pages'] ??= [];
            $data['pages']['NEW' . $key] = [
                'title' => 'Kurzmeldungen (' . $group['dkfz_number'] . ')',
                'pid' => $shortNewsStorageUid,
                'hidden' => 0,
                'doktype' => 254,
                'module' => 'news',
            ];
            $mountPointsForGroup = $group['db_mountpoints'] ? $group['db_mountpoints'] . ',' : '';
            $mountPointsForGroup .= $mountPointsForGroup . 'NEW' . $key;
            $data['be_groups'][$group['group_uid']]['db_mountpoints'] = $mountPointsForGroup;
        }

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($data, []);
        $dataHandler->process_datamap();

        return count($groups);
    }

    /**
     * @throws Exception
     */
    protected function getShortNewsStorageUid(): int
    {
        $shortNewsStorageUid = $this->phoneBookUtility->getDkfzExtensionSetting('pid_for_short_news_creation');
        if (!$shortNewsStorageUid || !MathUtility::canBeInterpretedAsInteger($shortNewsStorageUid)) {
            throw new Exception('Invalid pid for short news sys_folder', 1672730535);
        }
        return (int)$shortNewsStorageUid;
    }
}
