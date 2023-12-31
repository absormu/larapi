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
}
