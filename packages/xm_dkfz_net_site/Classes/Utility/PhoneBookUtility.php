<?php

namespace Xima\XmDkfzNetSite\Utility;

use DOMXPath;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Xima\XmDkfzNetSite\Domain\Model\User;
use function PHPUnit\Framework\throwException;

class PhoneBookUtility
{

    protected ExtensionConfiguration $extensionConfiguration;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function getXpath(): DOMXPath
    {
        $url = $this->getApiUrl();
        $xml = $this->fetchXmlFromApi($url);
        return $this->getXpathFromXml($xml);
    }

    protected function getXpathFromXml(string $xml): DOMXPath
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('x', 'http://schemas.datacontract.org/2004/07/TeleMailMvc.Models');
        return $xpath;
    }

    /**
     * @throws \TYPO3\CMS\Core\Exception
     */
    protected function fetchXmlFromApi(string $url): string
    {
        $xmlContent = file_get_contents($url);

        if (!$xmlContent) {
            throw new \TYPO3\CMS\Core\Exception(
                'Could not fetch XML from API ("' . $url . '")',
                1658212643
            );
        }

        return $xmlContent;
    }

    protected function getApiUrl(): string
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_site');
        return $extConf['phone_book_api_url'] ?? '';
    }

    public function updateFeUserFromXpath(User &$user, DOMXPath $xpath)
    {
        $cPersonNode = $xpath->query('//x:CPerson[x:Id="' . $user->getDkfzId() . '"]');

        if ($cPersonNode->count() !== 1) {
            return;
        }

        $firstName = $xpath->query('x:Vorname', $cPersonNode->item(0))->item(0)->nodeValue;
        if ($firstName) {
            $user->setFirstName($firstName);
        }
        $title = $xpath->query('x:Titel', $cPersonNode->item(0))->item(0)->nodeValue;
        if ($title) {
            $user->setTitle($title);
        }
        $lastName = $xpath->query('x:Nachname', $cPersonNode->item(0))->item(0)->nodeValue;
        if ($lastName) {
            $user->setLastName($lastName);
        }
        $mail = $xpath->query('x:Mail', $cPersonNode->item(0))->item(0)->nodeValue;
        if ($mail) {
            $user->setEmail($mail);
            $user->setUsername($mail);
        }
        $adAccountName = $xpath->query('x:AdAccountName', $cPersonNode->item(0))->item(0)->nodeValue;
        if ($adAccountName) {
            $user->setAdAccountName($adAccountName);
        }
        $genderMapping = ['Herr' => 1, 'Frau' => 2];
        $gender = $xpath->query('x:Anrede', $cPersonNode->item(0))->item(0)->nodeValue;
        if ($gender && in_array($gender, $genderMapping)) {
            $user->setGender($genderMapping[$gender]);
        }
        $deactivated = $xpath->query('x:Mail', $cPersonNode->item(0))->item(0)->nodeValue;
        $adAccountDeactivated = $xpath->query('x:Mail', $cPersonNode->item(0))->item(0)->nodeValue;
        $isHidden = filter_var($deactivated) || filter_var($adAccountDeactivated);
        $user->setDisable($isHidden);
    }

}
