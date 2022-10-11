<?php

namespace Xima\XmDkfzNetSite\Userfuncs;

class Tca
{
    /**
     * @param array<array<mixed>> $parameters
     */
    public function feUserLabel(array &$parameters): void
    {
        if (!isset($parameters['row'], $parameters['row']['first_name'], $parameters['row']['last_name'])) {
            return;
        }
        $parameters['title'] = $parameters['row']['last_name'] . ', ' . $parameters['row']['first_name'];
    }
}
