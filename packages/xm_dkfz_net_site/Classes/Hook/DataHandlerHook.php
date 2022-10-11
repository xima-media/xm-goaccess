<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use Xima\XmDkfzNetSite\Tca\TcaUtility;

class DataHandlerHook
{
    /**
     * @param array<mixed> $incomingFieldArray
     * @param string $table
     * @param int $id
     * @param DataHandler $parentObj
     */
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        mixed $id,
        DataHandler $parentObj
    ): void {
        if (isset($incomingFieldArray['tx_xmdkfznetsite_color']) && ($table === 'pages' || $table === 'tx_news_domain_model_news')) {
            $color = $incomingFieldArray['tx_xmdkfznetsite_color'];

            if ('' === $color || !in_array($color, TcaUtility::$colors, true)) {
                $incomingFieldArray['tx_xmdkfznetsite_color'] = TcaUtility::getRandomColor();
            }
        }
    }
}
