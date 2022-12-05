<?php

namespace Xima\XmDkfzNetSite\EventListener;


use Blueways\BwGuild\Event\InitializeUserUpdateEvent;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

class InitializeUserUpdateAction
{
    public function __invoke(InitializeUserUpdateEvent $event): void
    {
        $event->getPropertyMappingConfiguration()->forProperty('memberSince')->setTypeConverterOption(
            DateTimeConverter::class,
            DateTimeConverter::CONFIGURATION_DATE_FORMAT,
            'Y-m-d'
        );
    }
}
