<?php

namespace Xima\XmDkfzNetSite\Userfuncs;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class Tca
{
    /**
     * @param array<array<mixed>> $parameters
     * @return void
     */
    public function feUserLabel(array &$parameters): void
    {
        if (!isset($parameters['row'], $parameters['row']['first_name'], $parameters['row']['last_name'])) {
            return;
        }
        $parameters['title'] = $parameters['row']['last_name'] . ', ' . $parameters['row']['first_name'];
    }
}
