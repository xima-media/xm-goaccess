<?php

namespace Xima\XmDkfzNetSite\Command;

class ImportBeGroupCommand extends AbstractImportGroupCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ backend user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding be_groups');
    }

    /**
     * @return array<int, array{title: string, uid: int}>
     * @throws \Exception
     */
    protected function getAndCreateFileMountsForGroups(): array
    {
        $numbersToCreate = $this->compareResult->dkfzNumbersToCreate;
        if (!count($numbersToCreate)) {
            return [];
        }

        // validate storage identifier
        $storageIdentifier = $this->phoneBookUtility->getStorageIdentifierForGroups();
        $storageIdentifierParts = explode(':', $storageIdentifier);
        if (count($storageIdentifierParts) !== 2) {
            throw new \Exception('Invalid storage identifier for group folder', 1661761993);
        }

        // create missing folders
        $storage = $this->storageRepository->getStorageObject((int)$storageIdentifierParts[0]);
        $groupFolder = $storage->getFolder($storageIdentifierParts[1]);
        foreach ($numbersToCreate as $number) {
            if ($groupFolder->hasFolder($number)) {
                continue;
            }
            $groupFolder->createFolder($number);
        }

        // create missing fileMounts
        $dbFileMounts = $this->groupRepository->findAllFileMounts();
        $dbFileMountNumbers = array_map(function ($dbFileMount) {
            return $dbFileMount['title'];
        }, $dbFileMounts);
        $fileMountNumbersToCreate = array_filter($numbersToCreate, function ($number) use ($dbFileMountNumbers) {
            return !in_array($number, $dbFileMountNumbers);
        });
        $this->groupRepository->bulkInsertFileMounts($fileMountNumbersToCreate, $storageIdentifierParts[1]);

        // return all fileMounts
        return $this->groupRepository->findAllFileMounts();
    }

    protected function getSubGroup(): string
    {
        return $this->phoneBookUtility->getDkfzExtensionSetting('subgroup_for_imported_be_groups');
    }
}
