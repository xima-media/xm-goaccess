<?php
namespace Tpwd\KeSearchPremium\Hooks;

use Tpwd\KeSearch\Lib\Db;
use Tpwd\KeSearch\Lib\Pluginbase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ModifySearchWords
{

    /**
     * @var Pluginbase
     */
    protected $pObj;


    /**
     * search for synonyms and return the array by reference
     * @param array $wordsArray
     * @param Pluginbase $pObj
     */
    public function modifySearchWords(array &$wordsArray, Pluginbase $pObj)
    {
        // initialize this object
        $this->init($pObj);

        /**
         * proceed only if following is valid:
         * - wordsArray must be an array
         * - wordArray must contain entries
         * - sword can not be empty
         * - swords must be an array
         * - swords must contain entries
         * else wordArray will not be changed and will be returned untouched
         */
        if (is_array($wordsArray) &&
            count($wordsArray) &&
            !empty($wordsArray['sword']) &&
            is_array($wordsArray['swords']) &&
            count($wordsArray['swords'])
        ) {
            $searchPhrase = $wordsArray['sword'];

            // get synonyms of a single search word
            $synonyms = $this->getSynonyms($searchPhrase);

            // replace wordArray only when synonyms were found
            if (!empty($synonyms)) {
                // add the synonyms
                $wordsArray['wordsAgainst'] = implode('* | ', $synonyms) . '*';
                $wordsArray['scoreAgainst'] = implode(' | ', $synonyms);
            }
        }
    }

    /**
     * Initializes the hook
     * @param Pluginbase $pObj
     * @return void
     */
    public function init(Pluginbase $pObj)
    {
        $this->pObj = $pObj;
    }

    /**
     * get synonyms of given search word
     * @param string $searchWord
     * @return array Array containing synonyms
     */
    public function getSynonyms($searchWord)
    {
        if (is_string($searchWord) && !empty($searchWord)) {
            $searchWord = htmlspecialchars($searchWord);

            $table = 'tx_kesearchpremium_synonym';
            $fields = [
                'searchphrase',
                'synonyms'
            ];
            $queryBuilder = Db::getQueryBuilder($table);
            $synonymRows = $queryBuilder
                ->select(...$fields)
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq(
                        'searchphrase',
                        $queryBuilder->createNamedParameter($searchWord, \PDO::PARAM_STR)
                    )
                )
                ->orWhere(
                   'MATCH (synonyms) AGAINST (' . $queryBuilder->createNamedParameter($searchWord) . ')'
                )
                ->execute()
                ->fetchAll();

            $allSynonyms = [];
            foreach ($synonymRows as $row) {
                $synonyms = GeneralUtility::trimExplode(CHR(10), $row['synonyms'], true);
                foreach ($synonyms as $synonym) {
                    $allSynonyms[] = $synonym;
                }
                $allSynonyms[] = $row['searchphrase'];
            }
            return $allSynonyms;
        } else {
            return array();
        }
    }
}
