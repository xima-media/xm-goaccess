<?php

namespace Xima\XmDkfzNetSite\Domain\Model\Dto;

use DOMNode;
use DOMXPath;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

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

    public string $usergroup = '';

    /**
     * @var \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung[]
     */
    public array $abteilungen = [];

    /**
     * @param \DOMXPath $xpath
     * @param \DOMNode $userNode
     * @param array<int, array{dkfz_id: string, uid: int}> $dbGroups
     * @return PhoneBookPerson
     */
    public static function createFromXpathNode(DOMXPath $xpath, DOMNode $userNode, array $dbGroups): PhoneBookPerson
    {
        $person = new self();

        $hashOfNode = md5($userNode->nodeValue ?: '');
        $person->dkfzHash = $hashOfNode;

        $deactivated = self::getNodeValueFromXpath($xpath, $userNode, 'x:Deaktiviert');
        $adAccountDeactivated = self::getNodeValueFromXpath($xpath, $userNode, 'x:AdAccountGesperrt');
        $isHidden = filter_var($deactivated, FILTER_VALIDATE_BOOLEAN) || filter_var(
            $adAccountDeactivated,
            FILTER_VALIDATE_BOOLEAN
        );
        $person->disable = $isHidden;

        $dkfzId = self::getNodeValueFromXpath($xpath, $userNode, 'x:Id');
        if ($dkfzId) {
            $person->dkfzId = (int)$dkfzId;
        }

        $firstName = self::getNodeValueFromXpath($xpath, $userNode, 'x:Vorname');
        if ($firstName) {
            $person->firstName = $firstName;
        }
        $title = self::getNodeValueFromXpath($xpath, $userNode, 'x:Titel');
        if ($title) {
            $person->title = $title;
        }
        $lastName = self::getNodeValueFromXpath($xpath, $userNode, 'x:Nachname');
        if ($lastName) {
            $person->lastName = $lastName;
        }
        $mail = self::getNodeValueFromXpath($xpath, $userNode, 'x:Mail');
        if ($mail) {
            $person->email = $mail;
        }
        $adAccountName = self::getNodeValueFromXpath($xpath, $userNode, 'x:AdAccountName');
        if ($adAccountName) {
            $person->adAccountName = $adAccountName;
            $person->username = $adAccountName;
        }

        $genderMapping = ['Herr' => 1, 'Frau' => 2];
        $gender = self::getNodeValueFromXpath($xpath, $userNode, 'x:Anrede');
        if ($gender && in_array($gender, $genderMapping)) {
            $person->gender = $genderMapping[$gender];
        }

        $abteilung = self::getNodeValueFromXpath($xpath, $userNode, 'x:Abteilung');
        if ($abteilung) {
            $groupIds = PhoneBookUtility::getGroupIdsFromXmlAbteilungString($abteilung);
            foreach ($groupIds as $groupId) {
                $person->abteilungen[] = new PhoneBookAbteilung($groupId);
            }

            $filteredDbGroups = array_filter($dbGroups, function ($dbGroup) use ($groupIds) {
                return in_array($dbGroup['dkfz_id'], $groupIds);
            });
            $dbGroupUids = array_map(function ($dbGroup) {
                return $dbGroup['uid'];
            }, $filteredDbGroups);
            $person->usergroup = implode(',', $dbGroupUids);
        }

        return $person;
    }

    protected static function getNodeValueFromXpath(DOMXPath $xpath, DOMNode $userNode, string $nodeName): string
    {
        $node = $xpath->query($nodeName, $userNode);
        if (!$node) {
            return '';
        }
        return $node->item(0)?->nodeValue ?? '';
    }
}
