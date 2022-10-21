<?php
declare(strict_types=1);
namespace Tpwd\KeSearchPremium\Service;

class PartialWordsService
{
    public static function createPartialWordsFromContent(string $content, int $searchWordLength): string
    {
        $content = preg_replace("/[^[:alnum:][:space:]]/u", ' ', $content);
        $allWords = explode(' ', $content);
        $indexWords = [];
        foreach ($allWords as $word) {
            $word = str_replace("\n", '', $word);
            if (strlen($word) <= $searchWordLength) continue;
            if (str_starts_with($word, 'http://')) continue;
            if (str_starts_with($word, 'https://')) continue;
            if (in_array($word, $indexWords)) continue;
            if (intval($word) > 0) continue;
            $indexWords[] = $word;
            $wordLength = mb_strlen($word);
            for ($i=1; $i <= ($wordLength - $searchWordLength); $i++) {
                $indexWords[] = mb_substr($word, $i);
            }
        }
        return implode(' ', $indexWords);
    }
}