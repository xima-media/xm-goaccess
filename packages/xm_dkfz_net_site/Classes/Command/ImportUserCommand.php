<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportUserCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding fe_users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title($this->getDescription());

        return Command::SUCCESS;
    }
}
