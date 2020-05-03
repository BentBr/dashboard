<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Event extends Model
{
    use UsesUuid;

    //define fillable variables
    protected $fillable = [
        'client_id',
        'user_id',
        'event_type_id'
    ];

    /**
     * Every event has one eventType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventType()
    {
        return $this->belongsTo('\App\Models\EventType');
    }

    /**
     * Every Event has one Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('\App\Models\Client');
    }

    /**
     * Creating an Event based on infos given. Every event is assigned to one client.
     * Users (ids in this case) are optional)
     *
     * @param $type
     * @param $client_id
     * @param null $user_id
     * @return Exception|Event
     */
    public static function createNewEventByTypeClientUser($type, $client_id, $user_id = null)
    {
        try {
            //Getting EventType
            $eventType = EventType::where('type', $type)->first();

            $event = Event::create([
                'client_id'     => $client_id,
                'user_id'       => $user_id,
                'event_type_id' => $eventType->id
            ]);

            return $event;
        } catch(Exception $exception) {

            return $exception;
        }
    }
}
