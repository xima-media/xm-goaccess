<?php

namespace Xima\XmDkfzNetEvents\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Event extends AbstractEntity
{
    public string $title = '';

    public string $link = '';

    public string $author = '';

    public string $category = '';

    public string $description = '';

    public string $guid = '';

    public ?\DateTime $eventDate = null;

    public string $eventTimeString = '';

    public string $eventLanguage = '';

    public \DateTime $pubDate;

    public string $language = '';
}
