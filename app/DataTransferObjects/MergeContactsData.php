<?php

namespace App\DataTransferObjects;

use App\Http\Requests\MergeContactsFormRequest;
use App\Models\Contact;

class MergeContactsData {

    public Contact $updatedContact;

    public Contact $mergedContact;

    public static function fromRequest(MergeContactsFormRequest $request)
    {
        $dto = new self();

        $dto->updatedContact = Contact::findOrFail($request->input('updated_contact'));

        $dto->mergedContact = Contact::findOrFail($request->input('merged_contact'));

        return $dto;
    }
}
