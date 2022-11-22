<?php

namespace Xima\XmDkfzNetJobs\Domain\Model\Dto;

class JobOpening
{
    public int $id;

    public string $name;

    public string $location;

    public ?string $department;

    public ?string $earliestEntryDate;

    /**
     * @var \Xima\XmDkfzNetJobs\Domain\Model\Dto\JobWorkingTimes[]
     */
    public array $workingTimes = [];

    /**
     * @var \Xima\XmDkfzNetJobs\Domain\Model\Dto\JobCategories[]
     */
    public array $categories = [];
}
