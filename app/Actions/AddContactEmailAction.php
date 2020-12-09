<?php

namespace App\Actions;

use App\Models\Contact;
use App\DataTransferObjects\ContactEmailData;

class AddContactEmailAction {

    public function __invoke(Contact $contact, ContactEmailData $contactEmailData): Contact
    {
        $duplicate = $contact->email_addresses->firstWhere('email_address', $contactEmailData->email_address);

        if ($duplicate) {
            return $contact;
        }

        if ($contactEmailData->is_primary) {
            $contact->email_addresses = $contact->email_addresses->map(function ($email) {
                $email['is_primary'] = false;

                return $email;
            });
        }

        $contact->fill([
            'email_addresses' => $contact->email_addresses->push([
                'email_address' => $contactEmailData->email_address,
                'is_primary' => $contactEmailData->is_primary,
            ]),
        ])->save();

        return $contact;
    }
}
