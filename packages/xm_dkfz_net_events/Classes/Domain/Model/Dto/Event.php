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

    public ?\DateTime $eventdate = null;

    public string $eventtime = '';

    public string $eventlanguage = '';

    public ?\DateTime $pubdate = null;

    public string $language = '';
}
