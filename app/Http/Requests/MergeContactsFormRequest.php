<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeContactsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'updated_contact' => ['required', 'integer', 'exists:contacts,id'],
            'merged_contact' => ['required', 'integer', 'exists:contacts,id', 'different:updated_contact'],
        ];
    }
}
