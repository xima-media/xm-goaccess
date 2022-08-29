<?php

namespace Xima\XmDkfzNetSite\Command;

use function PHPUnit\Framework\throwException;

class ImportBeGroupCommand extends AbstractImportGroupCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ backend user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding be_groups');
    }

    /**
     * @throws \Exception
     */
    protected function createFileStorageForGroups(): void
    {
        if (!count($this->compareResult->dkfzNumbersToCreate)) {
            return;
        }
        $phoneBookAbteilungenToCreate = $this->phoneBookUtility->getPhoneBookAbteilungenByNumbers($this->compareResult->dkfzNumbersToCreate);
        $storageIdentifier = $this->phoneBookUtility->getStorageIdentifierForGroups();
        $storageIdentifierParts = explode(':', $storageIdentifier);

        if (count($storageIdentifierParts) !== 2) {
            throw new \Exception('Invalid storage identifier for group folder', 1661761993);
        }

        $storage = $this->storageRepository->getStorageObject((int)$storageIdentifierParts[0]);
        $groupFolder = $storage->getFolder($storageIdentifierParts[1]);

        if (!$groupFolder) {
            throw new \Exception('Folder for group folder generation not found', 1661761994);
        }

        foreach ($this->compareResult->dkfzNumbersToCreate as $number) {
            if ($groupFolder->hasFolder($number)) {
                continue;
            }
            $groupFolder->createFolder($number);
        }

    }

}
