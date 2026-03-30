<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReleaseEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'release_id',
        'event_type',
        'device_id',
        'ip_address',
    ];

    public function release()
    {
        return $this->belongsTo(Release::class);
    }
}
