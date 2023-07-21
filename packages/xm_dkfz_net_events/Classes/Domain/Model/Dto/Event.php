<?php

namespace Xima\XmDkfzNetEvents\Domain\Model\Dto;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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

    public function getLink(): string
    {
        if (str_starts_with($this->link, 'http://info/')) {
            return str_replace('http://info/', 'https://info.dkfz-heidelberg.de/', $this->link);
        }
        return $this->link;
    }

    public function getDescription(): string
    {
        if (str_contains($this->description, 'http')) {
            preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $this->description, $matches);
            foreach ($matches as $match) {
                if (isset($match[0]) && GeneralUtility::isValidUrl($match[0])) {
                    $urlParts = parse_url($match[0]);
                    $this->description = str_replace(
                        $match[0],
                        '<a href="' . $match[0] . '">' . $urlParts['host'] . '</a>',
                        $this->description
                    );
                }
            }
        }

        return $this->description;
    }
}
