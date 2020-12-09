<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Actions\AddContactEmailAction;
use App\Actions\DeleteContactEmailAction;
use App\DataTransferObjects\ContactEmailData;
use App\Http\Requests\ContactEmailFormRequest;

class ContactEmailController extends Controller
{
    public function store(
        Contact $contact,
        ContactEmailFormRequest $request,
        AddContactEmailAction $addContactEmailAction
    ) {
        $contactEmailData = ContactEmailData::fromRequest($request);

        $addContactEmailAction($contact, $contactEmailData);

        return back()->with('status', 'Email added');
    }

    public function destroy(
        Contact $contact,
        ContactEmailFormRequest $request,
        DeleteContactEmailAction $deleteContactEmailAction
    ) {
        $contactEmailData = ContactEmailData::fromRequest($request);

        $deleteContactEmailAction($contact, $contactEmailData);

        return back()->with('status', 'Email deleted');
    }
}
