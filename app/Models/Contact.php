<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $appends = ['primary_email', 'primary_phone'];

    protected $casts = [
        'email_addresses' => 'collection',
        'phone_numbers' => 'collection',
    ];

    protected $fillable = ['first_name', 'last_name', 'email_addresses', 'phone_numbers'];

    // public function addEmailAddress(EmailAddress $emailAddress)
    // {
    //     $duplicate = $this->email_addresses->first(function ($email) use ($emailAddress) {
    //         return $email->email_address === $emailAddress->email_address;
    //     });

    //     if ($duplicate) {
    //         return;
    //     }

    //     if ($emailAddress->is_primary) {
    //         $this->email_addresses = $this->email_addresses->map(function ($email) {
    //             $email['is_primary'] = false;

    //             return $email;
    //         });
    //     }

    //     $this->email_addresses = $this->email_addresses->merge([$emailAddress]);
    // }

    public function getPrimaryEmailAttribute($value)
    {
        if (is_null($this->email_addresses)) {
            return null;
        }

        return $this->email_addresses->firstWhere('is_primary')['email_address'];
    }

    public function getPrimaryPhoneAttribute($value)
    {
        if (is_null($this->phone_numbers)) {
            return null;
        }

        return $this->phone_numbers->firstWhere('is_primary')['phone_number'];
    }
}
