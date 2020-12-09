<?php

namespace App\Actions;

use App\Models\Contact;
use App\DataTransferObjects\ContactData;

class UpdateContactAction {

    private EnsureValidEmailAddressesAction $ensureValidEmailAddressesAction;

    private EnsureValidPhoneNumbersAction $ensureValidPhoneNumbersAction;

    public function __construct(
        EnsureValidEmailAddressesAction $ensureValidEmailAddressesAction,
        EnsureValidPhoneNumbersAction $ensureValidPhoneNumbersAction
    ) {
        $this->ensureValidEmailAddressesAction = $ensureValidEmailAddressesAction;
        $this->ensureValidPhoneNumbersAction = $ensureValidPhoneNumbersAction;
    }

    public function __invoke(Contact $contact, ContactData $contactData): Contact
    {
        $validEmailAddresses = ($this->ensureValidEmailAddressesAction)($contactData);

        $validPhoneNumbers = ($this->ensureValidPhoneNumbersAction)($contactData);

        $contact->fill([
            'first_name' => $contactData->first_name,
            'last_name' => $contactData->last_name,
            'email_addresses' => $validEmailAddresses,
            'phone_numbers' => $validPhoneNumbers,
        ])->save();

        return $contact;
    }
}
