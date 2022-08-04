<?php

namespace Xima\XmDkfzNetJobs\Domain\Model\Dto;

class Job
{
    public int $id = 0;

    public string $language = '';

    public string $position = '';

    /**
     * @var \Xima\XmDkfzNetJobs\Domain\Model\Dto\JobImage[]
     */
    public array $images = [];

    public ?\DateTime $startDate;

    public string $jobPublicationURL = '';

    public ?string $introduction;

    public ?string $tasks;

    public ?string $profile;

    public ?string $weOffer;

    public ?JobOpening $jobOpening;

    public function isNew(): bool
    {
        if (!$this->startDate) {
            return false;
        }

        $pastDate = new \DateTime();
        $pastDate->modify('-1 week');

        return $pastDate < $this->startDate;
    }
}
