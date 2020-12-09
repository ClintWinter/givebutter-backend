<?php

namespace App\Actions;

use App\Models\Contact;
use App\Actions\MergeContactEmailsAction;
use App\Actions\MergeContactPhonesAction;
use App\DataTransferObjects\MergeContactsData;

class MergeContactsAction {

    private MergeContactEmailsAction $mergeContactEmailsAction;

    private MergeContactPhonesAction $mergeContactPhonesAction;

    public function __construct(
        MergeContactEmailsAction $mergeContactEmailsAction,
        MergeContactPhonesAction $mergeContactPhonesAction
    ) {
        $this->mergeContactEmailsAction = $mergeContactEmailsAction;
        $this->mergeContactPhonesAction = $mergeContactPhonesAction;
    }

    public function __invoke(MergeContactsData $mergeContactsData)
    {
        /**
         * Prioritizing the preserved contact's primaries over the
         * contact being merged in.
         */
        $updatedContact = $mergeContactsData->updatedContact;
        $mergedContact = $mergeContactsData->mergedContact;

        $updatedContact->email_addresses = ($this->mergeContactEmailsAction)($updatedContact, $mergedContact);

        $updatedContact->phone_numbers = ($this->mergeContactPhonesAction)($updatedContact, $mergedContact);

        $mergedContact->delete();

        $updatedContact->save();

        return $updatedContact;
    }
}
