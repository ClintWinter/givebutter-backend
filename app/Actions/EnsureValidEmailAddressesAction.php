<?php

namespace App\Actions;

use App\DataTransferObjects\ContactData;

class EnsureValidEmailAddressesAction {

    public function __invoke(ContactData $contactData)
    {
        $uniqueEmails = $contactData->email_addresses->unique(function ($email) {
            return $email->email_address;
        });

        $primaryEmail = $uniqueEmails->first(function ($email) {
            return $email->is_primary;
        });

        return $uniqueEmails->map(function ($email) use ($primaryEmail) {
            if (! $email->is($primaryEmail)) {
                $email->is_primary = false;
            }

            return $email;
        });
    }
}
