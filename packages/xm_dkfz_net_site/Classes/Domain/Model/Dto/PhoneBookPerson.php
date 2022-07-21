<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

use DOMNode;
use DOMXPath;

class PhoneBookPerson
{
    public string $firstName = '';

    public string $lastName = '';

    public string $title = '';

    public string $username = '';

    public string $email = '';

    public string $location = '';

    public int $dkfzId = 0;

    public string $adAccountName = '';

    public string $dkfzHash = '';

    public bool $disable = false;

    public int $gender = 0;

    public static function createFromXpathNode(DOMXPath $xpath, DOMNode $userNode): static
    {
        $person = new static();

        $hashOfNode = md5($userNode->nodeValue);
        $person->dkfzHash = $hashOfNode;

        $deactivated = $xpath->query('x:Deaktiviert', $userNode)->item(0)->nodeValue;
        $adAccountDeactivated = $xpath->query('x:AdAccountGesperrt', $userNode)->item(0)->nodeValue;
        $isHidden = filter_var($deactivated, FILTER_VALIDATE_BOOLEAN) || filter_var(
                $adAccountDeactivated,
                FILTER_VALIDATE_BOOLEAN
            );
        $person->disable = $isHidden;

        $dkfzId = $xpath->query('x:Id', $userNode)->item(0)->nodeValue;
        if ($dkfzId) {
            $person->dkfzId = (int)$dkfzId;
        }

        $firstName = $xpath->query('x:Vorname', $userNode)->item(0)->nodeValue;
        if ($firstName) {
            $person->firstName = $firstName;
        }
        $title = $xpath->query('x:Titel', $userNode)->item(0)->nodeValue;
        if ($title) {
            $person->title = $title;
        }
        $lastName = $xpath->query('x:Nachname', $userNode)->item(0)->nodeValue;
        if ($lastName) {
            $person->lastName = $lastName;
        }
        $mail = $xpath->query('x:Mail', $userNode)->item(0)->nodeValue;
        if ($mail) {
            $person->email = $mail;
        }
        $adAccountName = $xpath->query('x:AdAccountName', $userNode)->item(0)->nodeValue;
        if ($adAccountName) {
            $person->adAccountName = $adAccountName;
            $person->username = $adAccountName;
        }

        $genderMapping = ['Herr' => 1, 'Frau' => 2];
        $gender = $xpath->query('x:Anrede', $userNode)->item(0)->nodeValue;
        if ($gender && in_array($gender, $genderMapping)) {
            $person->gender = $genderMapping[$gender];
        }

        return $person;
    }
}
