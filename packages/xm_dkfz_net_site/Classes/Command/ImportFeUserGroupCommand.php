<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportFeUserGroupCommand extends AbstractImportGroupCommand
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding fe_groups');
    }
}
