<?php

use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Creating default event types
        EventType::create([
            'type'  =>  'initialise_new_client',
        ]);
        EventType::create([
            'type'  =>  'new_login',
        ]);
        EventType::create([
            'type'  =>  'new_visitor',
        ]);
    }
}
