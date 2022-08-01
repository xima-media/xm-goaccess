<?php

namespace Xima\XmDkfzNetSite\Command;

class ImportFeUserCommand extends AbstractUserImportCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding fe_users');
    }
}
