<?php

namespace App\Actions;

use App\Models\Contact;
use App\DataTransferObjects\ContactPhoneData;
use Illuminate\Support\Collection;

class AddContactPhoneAction {

    public function __invoke(Contact $contact, ContactPhoneData $contactPhoneData): Contact
    {
        if (is_null($contact->phone_numbers)) {
            $contact->phone_numbers = new Collection();
        }

        $duplicate = $contact->phone_numbers->firstWhere('phone_number', $contactPhoneData->phone_number);

        if ($duplicate) {
            return $contact;
        }

        if ($contactPhoneData->is_primary) {
            $contact->phone_numbers = $contact->phone_numbers->map(function ($phone) {
                $phone['is_primary'] = false;

                return $phone;
            });
        }

        $contact->fill([
            'phone_numbers' => $contact->phone_numbers->push([
                'phone_number' => $contactPhoneData->phone_number,
                'is_primary' => $contactPhoneData->is_primary,
            ]),
        ])->save();

        return $contact;
    }
}
