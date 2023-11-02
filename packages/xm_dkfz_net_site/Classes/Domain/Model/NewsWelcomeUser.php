<?php

namespace Xima\XmDkfzNetSite\Domain\Model;

class NewsWelcomeUser extends \GeorgRinger\News\Domain\Model\News
{
    protected string $txXmdkfznetsiteColor = '';

    public function getTxXmdkfznetsiteColor(): string
    {
        return $this->txXmdkfznetsiteColor;
    }
}
