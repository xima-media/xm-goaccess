<?php

declare(strict_types=1);
return [
    \Xima\XmDkfzNetSite\Domain\Model\News::class => [
        'tableName' => 'tx_news_domain_model_news',
    ],
    \Xima\XmDkfzNetSite\Domain\Model\User::class => [
        'tableName' => 'fe_users',
    ],
    \Xima\XmDkfzNetSite\Domain\Model\BeUser::class => [
        'tableName' => 'be_users',
    ],
    \Xima\XmDkfzNetSite\Domain\Model\BeGroup::class => [
        'tableName' => 'be_groups',
    ],
];
