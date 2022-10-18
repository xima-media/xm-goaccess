<?php declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface;

class FormHook
{
    protected const FE_USER_EMAIL_FIELD = 'email';

    public function initializeFormElement(RenderableInterface $renderable): void
    {
        if ($renderable->getUniqueIdentifier() === 'generalfeedbackform-email') {
            $this->setDefaultEmail($renderable);
        }
        if ($renderable->getUniqueIdentifier() === 'generalfeedbackform-name') {
            $this->setDefaultName($renderable);
        }
    }

    protected function setDefaultEmail(RenderableInterface $renderable): void
    {
        if ($this->isFrontendUserLoggedIn()) {
            $frontendUserRecord = $this->getLoggedInFrontendUserRecord();
            $renderable->setDefaultValue($frontendUserRecord[self::FE_USER_EMAIL_FIELD] ?? '');
        }
    }

    protected function setDefaultName(RenderableInterface $renderable): void
    {
        if ($this->isFrontendUserLoggedIn()) {
            $frontendUserRecord = $this->getLoggedInFrontendUserRecord();
            $feUserFullName = $this->getFrontendUserNameString($frontendUserRecord);
            $renderable->setDefaultValue($feUserFullName);
        }
    }

    protected function isFrontendUserLoggedIn()
    {
        try {
            return GeneralUtility::makeInstance(Context::class)
                ->getPropertyFromAspect('frontend.user', 'isLoggedIn');
        } catch (AspectNotFoundException $e) {
            return false;
        }
    }

    protected function getLoggedInFrontendUserRecord(): ?array
    {
        $uid = GeneralUtility::makeInstance(Context::class)
            ->getPropertyFromAspect('frontend.user', 'id');

        return BackendUtility::getRecord('fe_users', $uid);
    }

    protected function getFrontendUserNameString(array $frontendUserRecord): string
    {
        $nameString = $frontendUserRecord['name'] ?? '';
        if (empty($nameString)) {
            $nameString = implode(' ', [
                $frontendUserRecord['first_name'] ?? null,
                $frontendUserRecord['last_name'] ?? null,
            ]);
        }
        return $nameString;
    }
}
