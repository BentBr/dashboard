<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use UsesUuid;

    public function events()
    {
        return $this->hasMany('\App\Models\Event');
    }
}
