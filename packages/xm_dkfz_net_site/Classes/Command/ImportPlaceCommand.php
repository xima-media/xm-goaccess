<?php

namespace Xima\XmDkfzNetSite\Command;

class ImportPlaceCommand extends AbstractUserImportCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ places from phone book API');
        $this->setHelp('Reads places from API and updates the corresponding tx_xmdkfznetsite_domain_model_place');
    }
}
