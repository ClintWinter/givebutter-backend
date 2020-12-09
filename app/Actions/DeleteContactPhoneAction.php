<?php

namespace App\Actions;

use App\Models\Contact;
use App\DataTransferObjects\ContactPhoneData;

class DeleteContactPhoneAction {

    public function __invoke(Contact $contact, ContactPhoneData $contactPhoneData): Contact
    {
        // get deleted
        $deletedPhone = $contact->phone_numbers->first(function ($value) use ($contactPhoneData) {
            return $value['phone_number'] === $contactPhoneData->phone_number;
        });

        // remove deleted
        $remainingPhones = $contact->phone_numbers->filter(function ($phone) use ($contactPhoneData) {
            return $phone['phone_number'] !== $contactPhoneData->phone_number;
        });

        // if deleted is primary, set new primary
        if (! $remainingPhones->firstWhere('is_primary')) {
            $primaryPhone = $remainingPhones->shift();
            $primaryPhone['is_primary'] = true;
            $remainingPhones->prepend($primaryPhone);
        }

        // update contact
        $contact->fill([
            'phone_numbers' => $remainingPhones,
        ])->save();

        return $contact;
    }
}
