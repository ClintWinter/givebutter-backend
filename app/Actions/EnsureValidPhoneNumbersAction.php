<?php

namespace App\Actions;

use App\DataTransferObjects\ContactData;

class EnsureValidPhoneNumbersAction {

    public function __invoke(ContactData $contactData)
    {
        $uniquePhones = $contactData->phone_numbers->unique(function ($phone) {
            return $phone->phone_number;
        });

        $primaryPhone = $uniquePhones->first(function ($phone) {
            return $phone->is_primary;
        });

        return $uniquePhones->map(function ($phone) use ($primaryPhone) {
            if (! $phone->is($primaryPhone)) {
                $phone->is_primary = false;
            }

            return $phone;
        });
    }
}
