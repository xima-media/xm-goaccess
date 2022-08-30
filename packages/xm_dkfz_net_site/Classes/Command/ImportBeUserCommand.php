<?php

namespace Xima\XmDkfzNetSite\Command;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class ImportBeUserCommand extends AbstractUserImportCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding be_users');
    }

    /**
     * @return int[]
     */
    public function getDefaultUserGroupUids(): array
    {
        $groups = $this->phoneBookUtility->getDkfzExtensionSetting('default_be_user_group');
        return GeneralUtility::intExplode(',', $groups);
    }

    protected function createContacts(): void
    {
    }

    public function updateContacts(): void
    {
    }

    protected function deleteContacts(): void
    {
    }
}
