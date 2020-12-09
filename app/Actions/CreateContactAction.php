<?php

namespace App\Actions;

use App\Models\Contact;
use App\DataTransferObjects\ContactData;

class CreateContactAction {

    private EnsureValidEmailAddressesAction $ensureValidEmailAddressesAction;

    private EnsureValidPhoneNumbersAction $ensureValidPhoneNumbersAction;

    public function __construct(
        EnsureValidEmailAddressesAction $ensureValidEmailAddressesAction,
        EnsureValidPhoneNumbersAction $ensureValidPhoneNumbersAction
    ) {
        $this->ensureValidEmailAddressesAction = $ensureValidEmailAddressesAction;
        $this->ensureValidPhoneNumbersAction = $ensureValidPhoneNumbersAction;
    }

    public function __invoke(ContactData $contactData): Contact
    {
        $validEmailAddresses = ($this->ensureValidEmailAddressesAction)($contactData);

        $validPhoneNumbers = ($this->ensureValidPhoneNumbersAction)($contactData);

        $contact = Contact::create([
            'first_name' => $contactData->first_name,
            'last_name' => $contactData->last_name,
            'email_addresses' => $validEmailAddresses,
            'phone_numbers' => $validPhoneNumbers,
        ]);

        return $contact;
    }
}
