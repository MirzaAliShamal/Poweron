<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function activeSubscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class)->where('subscribed', true);
    }

    public function inActiveSubscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class)->where('subscribed', false);
    }
}
