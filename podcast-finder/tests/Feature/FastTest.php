<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FastTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function  test_user_can_register()
    {
        $data = [
            "name" => "lal",
            "email" => "lal".time()."@gmail.com", 
            "password" => "1234567",
        ];
        
        $response = $this->postJson('/api/register', $data);
       $response->assertStatus(201)->assertJson([
            'message' => 'User registered successfully',
        ]);

        


    }



    
}
