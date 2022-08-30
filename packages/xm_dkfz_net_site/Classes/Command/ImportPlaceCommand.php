<?php

namespace Xima\XmDkfzNetSite\Command;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportPlaceCommand extends AbstractUserImportCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ places from phone book API');
        $this->setHelp('Reads places from API and updates the corresponding tx_xmdkfznetsite_domain_model_place');
    }

    /**
     * @return int[]
     */
    public function getDefaultUserGroupUids(): array
    {
        return [];
    }
}
