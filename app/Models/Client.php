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
     * Creating new client with unique name
     * On Success returns client_id
     *
     * @param $name
     * @return Exception
     */
    public static function createNewClientWithName($name)
    {
        try {
            $client = Client::create([
                'name'      => $name
            ]);

            return $client->id;
        } catch(Exception $exception) {

            return $exception;
        }
    }
}
