<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class ExternalLink extends AbstractEntity
{
    protected string $title = '';

    protected string $url = '';

    protected string $description = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
