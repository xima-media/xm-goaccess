<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportBeGroupCommand extends AbstractImportGroupCommand
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected function configure(): void
    {
        $this->setDescription('Import DKFZ backend user groups from phone book API');
        $this->setHelp('Reads groups from API and updates the corresponding be_groups');
    }
}
