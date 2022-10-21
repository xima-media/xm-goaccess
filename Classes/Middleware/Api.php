<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Christian Bülter
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Tpwd\KeSearchPremium\Middleware;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class Api
{
    /**
     * @param ServerRequestInterface $request
     */
    public function soapServer(ServerRequestInterface $request)
    {
        $remote = GeneralUtility::makeInstance(\Tpwd\KeSearchPremium\FetchIndexEntries::class);
        $service = GeneralUtility::makeInstance(\Tpwd\KeSearchPremium\Soapservice::class);
        $service->setClassname($remote);
        $server = new \SoapServer(null, array('uri' => 'http://tx_kesearchpremium_api'));
        $server->setObject($service);
        $server->handle();
    }

}
