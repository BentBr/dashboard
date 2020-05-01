<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebHookTest extends TestCase
{
    use WithFaker;
    use DatabaseMigrations;
    use RefreshDatabase;

    //
    //todo: In first version tests do not consume signatures, all events do have hard set-up Authorization
    //

    /**
     * Test for initialisation of new clients via WebHook
     *
     * @group initialise
     * @return void
     */
    public function testInitialiseNewClientViaWebHook()
    {
        $clientName = $this->faker->unique()->name;

        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys'[0]),        //using first one (test)
            'Event'             => 'initialise_new_client'
            ])->postJson(
                '/hook-me-baby-one-more-time',[                         //without pub key for now
                    'name'      => $clientName
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(201)
            ->assertJsonFragment([
                'status'    => 'success'
                ]
            );
        $this->assertDatabaseHas('clients', [
            'name'  => $clientName
            ]
        );
    }

    public function testNewLoginEventViaWebHook()
    {
        $response = $this->post('/hook-me-baby-one-more-time');
        $response->assertStatus(201);

    }

    public function testNewVisitorEventViaWebHook()
    {
        $response = $this->post('/hook-me-baby-one-more-time');

        $response->assertStatus(201);

    }
}
