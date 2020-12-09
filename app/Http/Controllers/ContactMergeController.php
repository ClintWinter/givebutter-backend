<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Actions\MergeContactsAction;
use App\DataTransferObjects\MergeContactsData;
use App\Http\Requests\MergeContactsFormRequest;

class ContactMergeController extends Controller
{
    public function store(
        MergeContactsFormRequest $request,
        MergeContactsAction $mergeContactsAction
    ) {
        $mergeContactsData = MergeContactsData::fromRequest($request);

        $mergeContactsAction($mergeContactsData);

        return back()->with('status', 'Contacts merged');
    }
}
