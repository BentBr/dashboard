<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use UsesUuid;

    /**
     * Every event has one EventType. One-to-many
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('\App\Models\Event');
    }
}
