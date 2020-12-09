<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\PhoneNumber;
use App\Models\EmailAddress;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_contact()
    {
        $data = [
            'first_name' => 'foobar',
            'last_name' => 'baz',
            'email_addresses' => ['foo@foo.com', 'bar@bar.com'],
            'primary_email' => 1,
        ];

        $this->post('/contacts/new', $data);

        $contact = Contact::where('first_name', $data['first_name'])->firstOrFail();

        $this->assertEquals('bar@bar.com', $contact->primary_email);

        $this->assertTrue($contact->email_addresses->contains('email_address', 'foo@foo.com'));

        $this->assertDatabaseHas('contacts', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    }

    /** @test */
    public function will_fail_validation_when_duplicate_emails()
    {
        $data = [
            'first_name' => 'foo',
            'last_name' => 'bar',
            'email_addresses' => ['foo@foo.com', 'foo@foo.com'],
            'primary_email' => 0,
        ];

        $this->post('contacts/new', $data)
            ->assertSessionHasErrors(['email_addresses.*']);
    }

    /** @test */
    public function will_fail_validation_when_email_aleady_exists_in_database()
    {
        $contact1 = Contact::factory()->create();

        $data = [
            'first_name' => 'foo',
            'last_name' => 'bar',
            'email_addresses' => [$contact1->primary_email],
            'primary_email' => 0,
        ];

        $this->post('contacts/new', $data)
            ->assertSessionHasErrors(['email_addresses.*']);

        $this->assertDatabaseMissing('contacts', [
            'first_name' => 'foo',
        ]);
    }

    /** @test */
    public function can_delete_an_email()
    {
        $contact = Contact::factory()->create([
            'email_addresses' => [
                $deleted = EmailAddress::factory()->make(['is_primary' => true]),
                EmailAddress::factory()->make(['is_primary' => false])
            ]
        ]);

        $data = ['email_address' => $deleted->email_address];

        $this->delete("contacts/{$contact->id}/delete-email", $data);

        $contact = $contact->refresh();

        $this->assertNotContains(
            $deleted->email_address,
            $contact->email_addresses->pluck('email_address')
        );

        $this->assertTrue($contact->email_addresses->first()['is_primary']);
    }

    /** @test */
    public function deleting_nonexistant_email_does_nothing()
    {
        $contact = Contact::factory()->create([
            'email_addresses' => [
                EmailAddress::factory()->make(['is_primary' => true]),
                EmailAddress::factory()->make(['is_primary' => false])
            ]
        ]);

        $data = ['email_address' => 'foo@bar.com'];

        $this->delete("contacts/{$contact->id}/delete-email", $data);

        $contact->refresh();

        $this->assertCount(2, $contact->email_addresses);
    }

    /** @test */
    public function can_add_an_email_to_a_contact()
    {
        $contact = Contact::factory()->create();

        $data = ['email_address' => 'foo@bar.com'];

        $this->put("contacts/{$contact->id}/add-email", $data)
            ->assertSessionHasNoErrors();

        $contact->refresh();

        $this->assertContains(
            'foo@bar.com', $contact->email_addresses->pluck('email_address')
        );
    }

    /** @test */
    public function adding_a_primary_email_updates_primary()
    {
        $contact = Contact::factory()->create([
            'email_addresses' => [
                EmailAddress::factory()->make(),
            ],
        ]);

        $newEmail = EmailAddress::factory()->make([
            'is_primary' => true,
        ]);

        $this->put("contacts/{$contact->id}/add-email", $newEmail->toArray())
            ->assertSessionHasNoErrors();

        $contact->refresh();

        $this->assertTrue($contact->primary_email === $newEmail->email_address);
    }

    /** @test */
    public function can_delete_a_phone_number()
    {
        $contact = Contact::factory()->create([
            'phone_numbers' => [
                $deleted = PhoneNumber::factory()->make(['is_primary' => true]),
                PhoneNumber::factory()->make(['is_primary' => false])
            ]
        ]);

        $this->delete("contacts/{$contact->id}/delete-phone", $deleted->toArray());

        $contact = $contact->refresh();

        $this->assertNotContains(
            $deleted->phone_number,
            $contact->phone_numbers->pluck('phone_number')
        );

        $this->assertTrue($contact->phone_numbers->first()['is_primary']);
    }

    /** @test */
    public function deleting_nonexistant_phone_does_nothing()
    {
        $contact = Contact::factory()->create([
            'phone_numbers' => [
                PhoneNumber::factory()->make(['is_primary' => true]),
                PhoneNumber::factory()->make(['is_primary' => false])
            ]
        ]);

        $data = ['phone_number' => 'foo@bar.com'];

        $this->delete("contacts/{$contact->id}/delete-phone", $data);

        $contact->refresh();

        $this->assertCount(2, $contact->phone_numbers);
    }

    /** @test */
    public function can_add_a_phone_to_a_contact()
    {
        $contact = Contact::factory()->create();

        $data = ['phone_number' => 'foo@bar.com'];

        $this->put("contacts/{$contact->id}/add-phone", $data)
            ->assertSessionHasNoErrors();

        $contact->refresh();

        $this->assertContains(
            'foo@bar.com', $contact->phone_numbers->pluck('phone_number')
        );
    }

    /** @test */
    public function adding_duplicate_phone_does_nothing()
    {
        $contact = Contact::factory()->create([
            'phone_numbers' => [
                $phone = PhoneNumber::factory()->make(),
            ],
        ]);

        $this->put("contacts/{$contact->id}/add-phone", $phone->toArray())
            ->assertSessionHasNoErrors();

        $contact->refresh();

        $this->assertCount(1, $contact->phone_numbers);
    }

    /** @test */
    public function adding_a_primary_phone_updates_primary()
    {
        $contact = Contact::factory()->create([
            'phone_numbers' => [
                PhoneNumber::factory()->make(),
            ],
        ]);

        $newPhone = PhoneNumber::factory()->make([
            'is_primary' => true,
        ]);

        $this->put("contacts/{$contact->id}/add-phone", $newPhone->toArray())
            ->assertSessionHasNoErrors();

        $contact->refresh();

        $this->assertTrue($contact->primary_phone === $newPhone->phone_number);
    }

    /** @test */
    public function a_contact_can_be_merged_into_another_contact()
    {
        $updatedContact = Contact::factory()->create([
            'phone_numbers' => [
                PhoneNumber::factory()->make(['is_primary' => true]),
            ],
        ]);
        $mergedContact = Contact::factory()->create([
            'phone_numbers' => [
                PhoneNumber::factory()->make(['is_primary' => true]),
            ],
        ]);

        $data = [
            'updated_contact' => $updatedContact->id,
            'merged_contact' => $mergedContact->id,
        ];

        $this->post("contacts/merge", $data)
            ->assertSessionHasNoErrors()
            ->assertStatus(302);

        $updatedContact->refresh();

        $this->assertDatabaseHas('contacts', [
            'first_name' => $updatedContact->first_name
        ]);

        $this->assertDatabaseMissing('contacts', [
            'first_name' => $mergedContact->first_name
        ]);

        $newContact = Contact::first();

        $this->assertCount(2, $updatedContact->email_addresses);

        $this->assertCount(2, $updatedContact->phone_numbers);

        $this->assertTrue($updatedContact->primary_email === $newContact->primary_email);

        $this->assertTrue($updatedContact->primary_phone === $newContact->primary_phone);
    }

    /** @test */
    public function merging_the_same_contact_fails_validation()
    {
        $updatedContact = Contact::factory()->create([
            'phone_numbers' => [
                PhoneNumber::factory()->make(['is_primary' => true]),
            ],
        ]);

        $data = [
            'updated_contact' => $updatedContact->id,
            'merged_contact' => $updatedContact->id,
        ];

        $this->post("contacts/merge", $data)
            ->assertSessionHasErrors(['merged_contact']);
    }
}
