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
}
