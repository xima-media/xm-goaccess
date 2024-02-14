<?php

namespace Xima\XmGoaccess\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Mapping extends AbstractEntity
{

    protected string $path = '';

    protected int $recordType = 0;

    protected int $page = 0;

    protected bool $regex = false;

    protected string $title = '';

    protected string $iconMarkup = '';

    protected string $pagePath = '';

    public function getPagePath(): string
    {
        return $this->pagePath;
    }

    public function setPagePath(string $pagePath): void
    {
        $this->pagePath = $pagePath;
    }

    public function getIconMarkup(): string
    {
        return $this->iconMarkup;
    }

    public function setIconMarkup(string $iconMarkup): void
    {
        $this->iconMarkup = $iconMarkup;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRecordType(): int
    {
        return $this->recordType;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function isRegex(): bool
    {
        return $this->regex;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

}