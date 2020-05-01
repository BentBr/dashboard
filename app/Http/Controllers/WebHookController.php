<?php

namespace App\Http\Controllers;

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
            case 'initialise_new_client':

                //validating format of name
                $request->validate([
                    'name'          => 'required|unique:clients'
                ]);

                //creating new client
                $client = Client::createNewClientWithName($request->name);
                if (! $client == false) {
                    return response()->json([
                        'status'    => 'success',
                        'client_id' => $client
                    ], 201);
                }

                break;
            case 'new_login':
                $request->validate([
                    'client_id'     => 'required|uuid',
                    'user_id'       => 'required|uuid'
                ]);
                break;
            case 'new_visitor':
                $request->validate([
                    'client_id'     => 'required|uuid',
                ]);
                break;
        }

        return response()->json([
            'status'        => 'error',
            'message'         => Lang::get('webhook.event_not_found'),
        ], 404);
    }
}
