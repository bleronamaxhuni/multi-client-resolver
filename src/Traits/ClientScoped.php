<?php

namespace Bleronamaxhuni\MultiClientResolver\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ClientScoped
{
    protected static function bootClientScoped()
    {
        static::addGlobalScope('client', function (Builder $builder) {
            if($client = app('currentClient')){
                $builder->where('client_id', $client->id);
            }
        });
    }

    public function scopeForClient(Builder $query, $client)
    {
        return $query->where('client_id', $client->id);
    }
}