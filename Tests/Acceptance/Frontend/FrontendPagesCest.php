<?php

namespace Xima\XmDkfzNet\Tests\Acceptance\Frontend;

use Xima\XmDkfzNet\Tests\Acceptance\Support\AcceptanceTester;

class FrontendPagesCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function firstPageIsRendered(AcceptanceTester $I): void
    {
        $I->amOnPage('/');
        $I->see('© Copyright DKFZ');
    }
}
