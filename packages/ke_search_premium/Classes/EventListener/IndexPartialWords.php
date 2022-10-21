<?php
declare(strict_types=1);
namespace Tpwd\KeSearchPremium\EventListener;

use Tpwd\KeSearch\Event\ModifyFieldValuesBeforeStoringEvent;
use Tpwd\KeSearch\Lib\SearchHelper;
use Tpwd\KeSearchPremium\Service\PartialWordsService;

class IndexPartialWords
{
    public function __invoke(ModifyFieldValuesBeforeStoringEvent $modifyFieldValuesBeforeStoringEvent)
    {
        $extConfPremium = SearchHelper::getExtConfPremium();
        if ($extConfPremium['enableNativeInWordSearch'] ?? false) {
            $extConf = SearchHelper::getExtConf();
            $searchWordLength = (int)$extConf['searchWordLength'] ?? 4;
            $fieldValues = $modifyFieldValuesBeforeStoringEvent->getFieldValues();
            $fieldValues['hidden_content'] .=
                ' ' .
                PartialWordsService::createPartialWordsFromContent(
                    $fieldValues['content'],
                    $searchWordLength
                );
            $modifyFieldValuesBeforeStoringEvent->setFieldValues($fieldValues);
        }
    }
}