<?php

namespace App\Rules;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EmailUnique implements Rule
{
    protected $contact;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = Contact::whereJsonContains(
            'email_addresses', ['email_address' => $value]
        );

        if ($this->contact) {
            $query = $query->where('id', '<>', $this->contact->id);
        }

        return ! $query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'An email is already in use. Duplicate contact prevented.';
    }
}
