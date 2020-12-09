<?php

namespace App\DataTransferObjects;

use App\Http\Requests\ContactEmailFormRequest;

class ContactEmailData {

    public string $email_address;

    public bool $is_primary = false;

    public static function fromRequest(ContactEmailFormRequest $request)
    {
        $dto = new self();

        $dto->email_address = $request->input('email_address');

        $dto->is_primary  = $request->input('is_primary', false);

        return $dto;
    }
}
