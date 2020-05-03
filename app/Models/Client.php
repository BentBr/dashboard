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
}
