<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use Xima\XmDkfzNetSite\Tca\TcaUtility;

/***
 *
 * This file is part of the "xm_dkfz_net_site" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Markus Hackel <markus.hackel@xima.de>, XIMA MEDIA GmbH
 *
 ***/
class DataHandlerHook
{
    /**
     * @param array $incomingFieldArray
     * @param string $table
     * @param int $id
     * @param DataHandler $parentObj
     */
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        $id,
        DataHandler $parentObj
    ): void {
        if ($table === 'pages' || $table === 'tx_news_domain_model_news') {
            $color = $incomingFieldArray['tx_xmdkfznetsite_color'] ?? null;

            if ('' === $color || !in_array($color, TcaUtility::$colors, true)) {
                $incomingFieldArray['tx_xmdkfznetsite_color'] = TcaUtility::getRandomColor();
            }
        }
    }
}
