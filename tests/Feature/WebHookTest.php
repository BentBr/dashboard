<?php

namespace Tests\Feature;

use App\Models\Client;
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
            'Authorization'     => config('authorizations.keys')[0],        //using first one (test)
            'Event'             => 'initialise_new_client'
            ])->postJson(
                '/api/hook-me-baby-one-more-time',[                     //without pub key for now
                    'name'      => $clientName
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(201)
            ->assertJsonFragment([
                'status'    => 'success',
                ]
            );
        $this->assertDatabaseHas('clients', [
            'name'  => $clientName
            ]
        );
    }

    //todo: initialising new clients do trigger an event as well.
    public function testInitialiseNewClientEventViaWebHook()
    {

    }

    /**
     * Test for missing Event in header -> is being checked via helper "WebHookCheck"
     *
     * @group fail
     * @return void
     */
    public function testFailMissingEvent()
    {
        $clientName = $this->faker->unique()->name;

        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys'[0]),        //using first one (test)
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                         //without pub key for now
                'name'      => $clientName
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(417)
            ->assertJsonFragment([
                    'status'    => 'error'
                ]
            );
    }

    /**
     * Checks if not existing event leads to proper error
     *
     * @return void
     * @group fail
     */
    public function testFailWrongEvent()
    {
        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys')[0],    //using first one (test)
            'Event'             => 'not_existing_one'
        ])->postJson(
            '/api/hook-me-baby-one-more-time');

        //checking for status, proper response
        $response->assertStatus(404)
            ->assertJsonFragment([
                    'status'    => 'error',
                    'message'   => 'Event not found'
                ]
            );
    }

    /**
     * Checks if missing authorization leads to proper fail
     *
     * @return void
     * @group fail
     */
    public function testFailMissingAuthorization()
    {
        //client setup
        $clientName = $this->faker->unique()->name;
        $client_id = Client::createNewClientWithName($clientName);
        $user_id = $this->faker->uuid;

        //
        //test for login
        //
        $response = $this->withHeaders([
            'Authorization'     => 'Im not existing',                       //using first one (test)
            'Event'             => 'new_login'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                         //without pub key for now
                'client_id'     => $client_id,
                'user_id'       => $user_id
            ]
        );

        //checking for status, proper response
        $response->assertStatus(401)
            ->assertJsonFragment([
                    'status'    => 'error',
                    'message'   => 'not authorized'
                ]
            );

        //
        //test for new client
        //
        $response = $this->withHeaders([
            'Authorization'     => 'Im not existing',                       //using first one (test)
            'Event'             => 'initialise_new_client'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                         //without pub key for now
                'name'          => $clientName,
            ]
        );

        //checking for status, proper response
        $response->assertStatus(401)
            ->assertJsonFragment([
                    'status'    => 'error',
                    'message'   => 'not authorized'
                ]
            );

        //
        //test for no (empty) authorization
        //
        $response = $this->withHeaders([
            'Authorization'     => '',                                      //using first one (test)
            'Event'             => 'initialise_new_client'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                         //without pub key for now
                'name'          => $clientName,
            ]
        );

        //checking for status, proper response
        $response->assertStatus(401)
            ->assertJsonFragment([
                    'status'    => 'error',
                    'message'   => 'not authorized'
                ]
            );
    }

    /**
     * Checking if Clients can only be inserted once to have than data validation going off
     *
     * @return void
     * @group fail
     */
    public function testFailCreateDuplicateClient()
    {
        //Client Setup
        $clientName = $this->faker->unique()->firstNameFemale;
        $client = Client::createNewClientWithName($clientName);

        //sending request with second time same name
        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys')[0],        //using first one (test)
            'Event'             => 'initialise_new_client'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                     //without pub key for now
                'name'      => $clientName
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(422)
            ->assertJsonFragment([
                    'message'    => 'The given data was invalid.'
                ]
            );
        $this->assertDatabaseHas('clients', [
                'name'  => $clientName
            ]
        );
    }

    /**
     * Checks if new login event is being created
     *
     * @return void
     * @group login
     */
    public function testNewLoginEventViaWebHook()
    {
        //client + user setup
        $clientName = $this->faker->unique()->firstNameFemale;
        $client = Client::createNewClientWithName($clientName);
        $user_id = $this->faker->uuid;

        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys')[0],        //using first one (test)
            'Event'             => 'new_login'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                             //without pub key for now
                'client_id'     => $client->id,
                'user_id'       => $user_id
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(201)
            ->assertJsonFragment([
                    'status'    => 'success'
                ]
            );

        //to make sure data has been persisted
        $this->assertDatabaseHas('events', [
                'user_id'       => $user_id,
                'client_id'     => $client->id
            ]
        );
    }

    /**
     * Checks if new visit event is being created
     *
     * @return void
     * @group visit
     */
    public function testNewVisitorEventViaWebHook()
    {
        //client + user setup
        $clientName = $this->faker->unique()->firstNameFemale;
        $client = Client::createNewClientWithName($clientName);

        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys')[0],        //using first one (test)
            'Event'             => 'new_visitor'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                             //without pub key for now
                'client_id'     => $client->id,
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(201)
            ->assertJsonFragment([
                    'status'    => 'success'
                ]
            );

        //to make sure data has been persisted
        $this->assertDatabaseHas('events', [
                'client_id'     => $client->id
            ]
        );
    }

    /**
     * Checks if login event with not-existing client_id gets proper 404
     *
     * @group fail
     * @return void
     */
    public function testLoginWithWrongClient()
    {
        //client + user setup
        $clientName = $this->faker->unique()->firstNameFemale;
        $client = Client::createNewClientWithName($clientName);
        $user_id = $this->faker->uuid;
        $fakeUuid = $this->faker->uuid;

        $response = $this->withHeaders([
            'Authorization'     => config('authorizations.keys')[0],        //using first one (test)
            'Event'             => 'new_login'
        ])->postJson(
            '/api/hook-me-baby-one-more-time',[                             //without pub key for now
                'client_id'     => $fakeUuid,
                'user_id'       => $user_id,
            ]
        );

        //checking for status, proper response and database's content
        $response->assertStatus(404)
            ->assertJsonFragment([
                    'status'    => 'error'
                ]
            );

    }

    //todo: should get 422 (validation)
    public function testLoginWithMalformedUserUuid()
    {

    }
}
