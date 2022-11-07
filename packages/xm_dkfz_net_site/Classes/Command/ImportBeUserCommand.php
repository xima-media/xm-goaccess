<?php

namespace Xima\XmDkfzNetSite\Command;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportBeUserCommand extends AbstractUserImportCommand
{
    protected function configure(): void
    {
        $this->setDescription('Import DKFZ be_user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding be_users');
    }

    /**
     * @return int[]
     */
    public function getDefaultUserGroupUids(): array
    {
        $groups = $this->phoneBookUtility->getDkfzExtensionSetting('default_be_user_group');
        return GeneralUtility::intExplode(',', $groups);
    }

    protected function createContacts(): void
    {
    }

    public function updateContacts(): void
    {
    }

    protected function deleteContacts(): void
    {
    }

    protected function checkUserAccess(): void
    {
        $this->fakeIntranetGroupForUsers();
        $this->skipNewUsersWithoutIntranetGroup();
        $this->deleteUpdateUsersWithoutIntranetGroup();
    }

    protected function skipNewUsersWithoutIntranetGroup(): void
    {
        $phoneBookEntriesToAdd = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToCreate);
        foreach ($phoneBookEntriesToAdd as $entry) {
            if ($entry->gruppen !== 'Intranet-Redakteure') {
                $index = array_search($entry->id, $this->compareResult->dkfzIdsToCreate);
                unset($this->compareResult->dkfzIdsToCreate[$index]);
                $this->compareResult->dkfzIdsToSkip[] = $entry->id;
            }
        }
    }

    protected function deleteUpdateUsersWithoutIntranetGroup(): void
    {
        $phoneBookEntriesToUpdate = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToUpdate);
        foreach ($phoneBookEntriesToUpdate as $entry) {
            if ($entry->gruppen !== 'Intranet-Redakteure') {
                $index = array_search($entry->id, $this->compareResult->dkfzIdsToUpdate);
                unset($this->compareResult->dkfzIdsToUpdate[$index]);
                $this->compareResult->dkfzIdsToDelete[] = $entry->id;
            }
        }
    }

    protected function fakeIntranetGroupForUsers(): void
    {
        $usersToFakeGroupFor = [
            'm.ferg@dkfz-heidelberg.de',
            'm.steiner@dkfz-heidelberg.de',
            'h.metzger@dkfz-heidelberg.de',
            'a.wenskus@dkfz-heidelberg.de',
            'hollyn.hartlep@dkfz-heidelberg.de',
            'larissa.fritzenschaf@kitz-heidelberg.de',
            'emre.turpcu@dkfz-heidelberg.de',
            's.latzko@dkfz-heidelberg.de',
            'j.kapeller@dkfz-heidelberg.de',
            'a.schweickert@dkfz-heidelberg.de',
            'g.schulze-koenig@dkfz-heidelberg.de',
            'a.hemker@dkfz-heidelberg.de',
            'annalena.riedasch@dkfz-heidelberg.de',
            'd.sandel@dkfz-heidelberg.de',
            'j.bopp@dkfz-heidelberg.de',
            'c.joerg@dkfz-heidelberg.de',
            'a.schmitt@dkfz-heidelberg.de',
            'anita.schoenpflug@dkfz-heidelberg.de',
            'jennifer.engel@dkfz-heidelberg.de',
            's.kreimeyer@dkfz-heidelberg.de',
            'h.lenz@dkfz-heidelberg.de',
            'a.krauss@dkfz-heidelberg.de',
            'laura.pelz@dkfz-heidelberg.de',
            'b.janssens@dkfz-heidelberg.de',
            'm.guerth@dkfz-heidelberg.de',
        ];

        $phoneBookEntriesToAdd = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToCreate);
        foreach ($phoneBookEntriesToAdd as $entry) {
            if (in_array($entry->mail, $usersToFakeGroupFor)) {
                $entry->gruppen = 'Intranet-Redakteure';
                $this->phoneBookUtility->updatePhoneBookEntry($entry);
            }
        }

        $phoneBookEntriesToUpdate = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToCreate);
        foreach ($phoneBookEntriesToUpdate as $entry) {
            if (in_array($entry->mail, $usersToFakeGroupFor)) {
                $entry->gruppen = 'Intranet-Redakteure';
                $this->phoneBookUtility->updatePhoneBookEntry($entry);
            }
        }
    }
}
