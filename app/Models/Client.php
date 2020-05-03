<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Client extends Model
{
    use UsesUuid;

    //define fillable variables
    protected $fillable = [
        'name',
    ];

    /**
     * One Client has many events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('\App\Models\Event');
    }

    /**
     * Creating new client with unique (check in validation of controller) name
     * On Success returns client object
     *
     * @param $name
     * @return Exception|\Illuminate\Http\JsonResponse
     */
    public static function createNewClientWithName($name)
    {
        try {

            $client = Client::create([
                'name'      => $name
            ]);

            if(! $client == false) {

                return $client;
            }

        } catch(Exception $exception) {

            return $exception;
        }
    }

    /**
     * Retrieves needed data from DB. Calculates statistics about login and visitors.
     *
     * @return Client[]|Exception|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public static function getAllClientsWithLoginVisitEventsCount()
    {
        try {

            //all clients
            $clients = Client::all();
            //maybe no clients here..
            if (count($clients) == 0) {

                return response()->json([
                    'status'    => 'error',
                    'message'   => 'no clients found'
                ], 404);
            }

            //interested types of events
            $loginEventType = EventType::where('type', 'new_login')->firstOrFail();
            $visitEventType = EventType::where('type', 'new_visitor')->firstOrFail();

            //count for events
            foreach ($clients as $client) {

                //counting and saving results into $client object for both defined events
                $client->count_visits = count(Event::where('client_id', $client->id)
                    ->where('event_type_id', $visitEventType->id)
                    ->get());
                $client->count_login = count(Event::where('client_id', $client->id)
                    ->where('event_type_id', $loginEventType->id)
                    ->get());
            }

            return $clients;

        } catch(Exception $exception) {

            return $exception;
        }
    }
}
