<?php

namespace Xima\XmDkfzNetEvents\Domain\Model\Dto;

class Event
{
    public string $title = '';

    public string $link = '';

    public string $author = '';

    public string $category = '';

    public string $description = '';

    public string $guid = '';

    public ?\DateTime $eventDate = null;

    public string $eventTime = '';

    public string $eventLanguage = '';

    public ?\DateTime $pubDate = null;

    public string $language = '';
}
