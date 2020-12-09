<?php

namespace App\Actions;

use App\Models\Contact;

class MergeContactPhonesAction {

    public function __invoke(Contact $updatedContact, Contact $mergedContact)
    {
        if (is_null($updatedContact->phone_numbers)) {
            return $mergedContact->phone_numbers;
        }

        if (is_null($mergedContact->phone_numbers)) {
            return $updatedContact->phone_numbers;
        }

        $primaryPhone = $updatedContact->primary_phone;

        $updatedContact->phone_numbers = $updatedContact->phone_numbers->merge($mergedContact->phone_numbers);

        return $updatedContact->phone_numbers->map(function ($phone) use ($primaryPhone) {
            $phone['is_primary'] = false;

            if ($phone['phone_number'] === $primaryPhone) {
                $phone['is_primary'] = true;
            }

            return $phone;
        });
    }
}
