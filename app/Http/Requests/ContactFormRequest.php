<?php

namespace App\Http\Requests;

use App\Rules\EmailUnique;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
        $maxPrimaryEmail = $this->input('email_addresses', [])
            ? count($this->input('email_addresses'))-1
            : 0;
        $maxPrimaryPhone = $this->input('email_addresses', [])
            ? count($this->input('phone_numbers', []))-1
            : 0;

        return [
            'first_name' => ['required', 'min:2', 'max:64'],
            'last_name' => ['required', 'min:2', 'max:64'],

            'email_addresses' => ['required', 'min:1', 'array'],
            'email_addresses.*' => ['required', 'email', 'max:128', 'distinct', new EmailUnique($this->contact)],
            'primary_email' => ['required', 'integer', "max:{$maxPrimaryEmail}"],

            'phone_numbers' => ['nullable', 'min:1', 'array'],
            'phone_numbers.*' => ['nullable', 'min:10', 'max:20'],
            'primary_phone' => ['required_with:phone_numbers', 'integer', "max:{$maxPrimaryPhone}"],
        ];
    }

    public function messages()
    {
        return [
            'email_addresses.*.distinct' => 'Cannot have the same email more than once.',
            'primary_email.max' => 'A valid primary email must be selected.',
            'primary_phone.max' => 'A valid primary phone must be selected.',
        ];
    }
}
