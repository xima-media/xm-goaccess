<?php

namespace Xima\XmDkfzNet\Tests\Acceptance;

use Xima\XmDkfzNet\Tests\Acceptance\Support\AcceptanceTester;

class FrontendPagesCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function firstPageIsRendered(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Open source, enterprise CMS delivering  content-rich digital experiences on any channel,  any device, in any language');
        $I->click('Customize');
        $I->see('Incredible flexible');
    }
}
