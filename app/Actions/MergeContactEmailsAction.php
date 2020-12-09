<?php

namespace App\Actions;

use App\Models\Contact;

class MergeContactEmailsAction {

    public function __invoke(Contact $updatedContact, Contact $mergedContact)
    {
        if (is_null($updatedContact->email_addresses)) {
            return $mergedContact->email_addresses;
        }

        if (is_null($mergedContact->email_addresses)) {
            return $updatedContact->email_addresses;
        }

        $primaryEmail = $updatedContact->primary_email;

        $updatedContact->email_addresses = $updatedContact->email_addresses->merge($mergedContact->email_addresses);

        return $updatedContact->email_addresses->map(function ($email) use ($primaryEmail) {
            $email['is_primary'] = false;

            if ($email['email_address'] === $primaryEmail) {
                $email['is_primary'] = true;
            }

            return $email;
        });
    }
}
