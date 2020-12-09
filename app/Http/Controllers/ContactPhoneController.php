<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Actions\AddContactPhoneAction;
use App\Actions\DeleteContactPhoneAction;
use App\DataTransferObjects\ContactPhoneData;
use App\Http\Requests\ContactPhoneFormRequest;

class ContactPhoneController extends Controller
{
    public function store(
        Contact $contact,
        ContactPhoneFormRequest $request,
        AddContactPhoneAction $addContactPhoneAction
    ) {
        $contactPhoneData = ContactPhoneData::fromRequest($request);

        $addContactPhoneAction($contact, $contactPhoneData);

        return back()->with('status', 'Phone added');
    }

    public function destroy(
        Contact $contact,
        ContactPhoneFormRequest $request,
        DeleteContactPhoneAction $deleteContactPhoneAction
    ) {
        $contactPhoneData = ContactPhoneData::fromRequest($request);

        $deleteContactPhoneAction($contact, $contactPhoneData);

        return back()->with('status', 'Phone deleted');
    }
}
