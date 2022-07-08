<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

class News extends \GeorgRinger\News\Domain\Model\News
{
    protected string $txXmdkfznetsiteColor = '';

    public function getTxXmdkfznetsiteColor(): string
    {
        return $this->txXmdkfznetsiteColor;
    }
}
