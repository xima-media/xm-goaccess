<?php

namespace Xima\XmDkfzNetSite\Command;

class ImportFeUserGroupCommand extends AbstractImportGroupCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ frontend user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding fe_groups');
    }
}
