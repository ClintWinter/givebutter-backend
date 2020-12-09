<?php

namespace App\Actions;

use App\Models\Contact;
use App\DataTransferObjects\ContactEmailData;

class DeleteContactEmailAction {

    public function __invoke(Contact $contact, ContactEmailData $contactEmailData): Contact
    {
        // get deleted
        $deletedEmail = $contact->email_addresses->first(function ($value) use ($contactEmailData) {
            return $value['email_address'] === $contactEmailData->email_address;
        });

        // remove deleted
        $remainingEmails = $contact->email_addresses->filter(function ($email) use ($contactEmailData) {
            return $email['email_address'] !== $contactEmailData->email_address;
        });

        // if deleted is primary, set new primary
        if (! $remainingEmails->firstWhere('is_primary')) {
            $primaryEmail = $remainingEmails->shift();
            $primaryEmail['is_primary'] = true;
            $remainingEmails->prepend($primaryEmail);
        }

        // update contact
        $contact->fill([
            'email_addresses' => $remainingEmails,
        ])->save();

        return $contact;
    }
}
