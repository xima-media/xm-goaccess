<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2014 Christian Bülter
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Tpwd\KeSearchPremium;

use Tpwd\KeSearch\Lib\SearchHelper;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class Soapservice
{
    protected $class_name = '';
    protected $authenticated = FALSE;
    public $extConfPremium = [];

    public function __construct()
    {
        $this->extConfPremium = SearchHelper::getExtConfPremium();
    }

    /**
     * set class name for soap service
     * @param string $class_name
     */
    public function setClassname($class_name)
    {
        $this->class_name = $class_name;
    }

    /**
     * check authentication by verifying the header
     * @param object $header
     */
    public function AuthHeader($header)
    {
        if ($header->username == $this->extConfPremium['apiUsername']
            && $header->password == $this->extConfPremium['apiPassword']) {
            $this->authenticated = TRUE;
        }
    }

    /**
     * call method from service class
     * @param string $method_name
     * @param $arguments
     * @return
     */
    public function __call($method_name, $arguments)
    {
        if (!$this->extConfPremium['enableApi']) {
            return array('error' => 'API is not activated');
        }

        if (!$this->extConfPremium['apiUsername'] || !$this->extConfPremium['apiPassword']) {
            return array('error' => 'username or password not set in server configuration');
        }

        if (!$this->authenticated) {
            return array('error' => 'not authenticated');
        }

        if (!method_exists($this->class_name, $method_name) || !is_callable(array($this->class_name, $method_name))) {
            return array('error' => 'method not found');
        }

        return call_user_func_array(array($this->class_name, $method_name), $arguments);
    }
}
