<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testContactSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => 'Muhamad',
            'last_name' => 'Ulil Absor',
            'email' => 'mua@gmail.com',
            'phone' => '081291808440',
        ],[
            'Authorization' => 'test'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'first_name' => 'Muhamad',
                    'last_name' => 'Ulil Absor',
                    'email' => 'mua@gmail.com',
                    'phone' => '081291808440',
                ]
            ]);
    }

    public function testContactFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'Ulil Absor',
            'email' => 'muagmail.com',
            'phone' => '081291808440',
        ],[
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'first_name' => [
                        'The first name field is required.'
                    ],
                    'email' => [
                        'The email field must be a valid email address.'
                    ],
                ]
            ]);
    }

    public function testContactUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => 'Muhamad',
            'last_name' => 'Ulil Absor',
            'email' => 'muagmail.com',
            'phone' => '081291808440',
        ],[
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'first_name' => 'test',
                    'last_name' => 'test',
                    'email' => 'test@gmail.com',
                    'phone' => '081291808440',
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id + 1, [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'not found'
                    ],
                ]
            ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id + 1, [
            'Authorization' => 'test2'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'not found'
                    ],
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
                'first_name' => 'test2',
                'last_name' => 'test2',
                'email' => 'test2@gmail.com',
                'phone' => '081291808442',
            ],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'first_name' => 'test2',
                    'last_name' => 'test2',
                    'email' => 'test2@gmail.com',
                    'phone' => '081291808442',
                ]
            ]);
    }

    public function testUpdateValidatorError()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
                'first_name' => '',
                'last_name' => 'test2',
                'email' => 'test2@gmail.com',
                'phone' => '081291808442',
            ],[
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'first_name' => [
                        "The first name field is required."
                    ],
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id, [],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . ($contact->id + 1), [],[
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }
}
