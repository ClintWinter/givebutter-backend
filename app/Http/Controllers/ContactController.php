<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Actions\CreateContactAction;
use App\Actions\UpdateContactAction;
use App\DataTransferObjects\ContactData;
use App\Http\Requests\ContactFormRequest;

class ContactController extends Controller
{
    public function create()
    {
        return view('contacts.new');
    }

    public function store(ContactFormRequest $request, CreateContactAction $createContactAction)
    {
        $contactData = ContactData::fromRequest($request);

        $createContactAction($contactData);

        return back()->with('status', 'Contact created');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', [
            'contact' => $contact,
        ]);
    }

    public function update(
        Contact $contact,
        ContactFormRequest $request,
        UpdateContactAction $updateContactAction
    ) {
        $contactData = ContactData::fromRequest($request);

        $updateContactAction($contact, $contactData);

        return back()->with('status', 'Contact updated');
    }
}
