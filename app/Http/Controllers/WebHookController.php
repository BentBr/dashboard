<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Lang;

class WebHookController extends Controller
{
    public function event(Request $request)
    {
        //evaluating event
        $event = $request->header('Event');

        switch ($event) {
            /*
             *
             * Handling initialise_new_client event
             *
             */
            case 'initialise_new_client':

                //validating format of name
                $request->validate([
                    'name'          => 'required|unique:clients'
                ]);

                //creating client + event
                $client = Client::createNewClientWithName($request->name);
                if (! $client == false) {

                    $event = Event::createNewEventByTypeClientUser('initialise_new_client', $client->id);

                    if (! $event == false) {

                        return response()->json([
                            'status'    => 'success',
                            'client_id' => $client->id
                        ], 201);
                    }
                }

                //if something didn't go after plan
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'something unknown went wrong :D '
                ], 500);
                break;

            /*
             *
             * Handling new_login event
             *
             */
            case 'new_login':

                //validating payload
                $request->validate([
                    'client_id'     => 'required|uuid',
                    'user_id'       => 'required|uuid'
                ]);

                //check if client exists
                $this->checkForClient($request->client_id);

                $event = Event::createNewEventByTypeClientUser('new_login', $request->client_id, $request->user_id);
                if (! $event == false) {

                    return response()->json([
                        'status'    => 'success',
                        'event_id'  => $event->id
                    ], 201);
                }

                //if something didn't go after plan
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'something unknown went wrong :D '
                ], 500);
                break;
            /*
             *
             * Handling new_visitor event
             *
             */
            case 'new_visitor':

                //validating payload
                $request->validate([
                    'client_id'     => 'required|uuid',
                ]);

                //check if client exists
                $this->checkForClient($request->client_id);

                $event = Event::createNewEventByTypeClientUser('new_visitor', $request->client_id);
                if (! $event == false) {
                    return response()->json([
                        'status'    => 'success',
                        'event_id'  => $event->id
                    ], 201);
                }

                //if something didn't go after plan
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'something unknown went wrong :D '
                ], 500);
                break;        }

        /*
         * Making sure no event get slipped. Every request of WebHook service is triggering a proper response
         */
        return response()->json([
            'status'          => 'error',
            'message'         => Lang::get('webhook.event_not_found'),
        ], 404);
    }

    private function checkForClient($client_id)
    {
        //in case of fails -> 404
        $client = Client::findOrfail($client_id);

        if (! $client == false) {

            return true;
        }
    }
}
