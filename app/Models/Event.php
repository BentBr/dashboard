<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use UsesUuid;

    public function eventType()
    {
        return $this->belongsTo('\App\Models\EventType');
    }

}
