<?php

namespace App\DataTransferObjects;

use App\Models\PhoneNumber;
use App\Models\EmailAddress;
use Illuminate\Support\Collection;
use App\Http\Requests\ContactFormRequest;

class ContactData {

    public string $first_name;

    public string $last_name;

    public Collection $email_addresses;

    public Collection $phone_numbers;

    public static function fromRequest(ContactFormRequest $request)
    {
        $dto = new self();

        $dto->first_name = $request->input('first_name');

        $dto->last_name = $request->input('last_name');

        $primaryId = (int) $request->input('primary_email');
        $dto->email_addresses = collect($request->input('email_addresses'))
            ->map(function ($email, $key) use ($primaryId) {
                return EmailAddress::make([
                    'email_address' => $email,
                    'is_primary' => $key === $primaryId
                ]);
            });

        $primaryId = (int) $request->input('primary_phone');
        $dto->phone_numbers = collect($request->input('phone_numbers'))
            ->map(function ($phone, $key) use ($primaryId) {
                return PhoneNumber::make([
                    'phone_number' => $phone,
                    'is_primary' => $key === $primaryId
                ]);
            });

        return $dto;
    }
}
