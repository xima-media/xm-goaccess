<?php

namespace Xima\XmDkfzNetJobs\Domain\Model\Dto;

class Job
{

    public int $id;

    public string $language;

    /**
     * @var \Xima\XmDkfzNetJobs\Domain\Model\Dto\JobImage[]
     */
    public array $images;
}
