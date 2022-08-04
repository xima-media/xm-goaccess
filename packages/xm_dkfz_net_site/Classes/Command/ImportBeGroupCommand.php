<?php

namespace Xima\XmDkfzNetSite\Command;

class ImportBeGroupCommand extends AbstractImportGroupCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ backend user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding be_groups');
    }
}
