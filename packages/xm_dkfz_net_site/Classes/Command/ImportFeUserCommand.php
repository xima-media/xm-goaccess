<?php

namespace Xima\XmDkfzNetSite\Command;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportFeUserCommand extends AbstractUserImportCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ fe_user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding fe_users');
    }

    /**
     * @return int[]
     */
    public function getDefaultUserGroupUids(): array
    {
        $groups = $this->phoneBookUtility->getDkfzExtensionSetting('default_fe_user_group');
        return GeneralUtility::intExplode(',', $groups);
    }
}
