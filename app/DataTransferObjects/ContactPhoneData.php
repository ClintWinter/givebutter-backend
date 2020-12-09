<?php

namespace App\DataTransferObjects;

use App\Http\Requests\ContactPhoneFormRequest;

class ContactPhoneData {

    public string $phone_number;

    public bool $is_primary = false;

    public static function fromRequest(ContactPhoneFormRequest $request)
    {
        $dto = new self();

        $dto->phone_number = $request->input('phone_number');

        $dto->is_primary  = $request->input('is_primary', false);

        return $dto;
    }
}
