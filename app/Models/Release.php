<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    protected $fillable = [
        'app_name',
        'platform',
        'app_version',
        'bundle_version',
        'bundle_url',
        'bundle_hash',
        'bundle_file_name',
        'environment',
        'is_enabled',
        'rollout_percentage',
        'promoted_from_id',
        'is_current',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_current' => 'boolean',
        'rollout_percentage' => 'integer',
    ];

    public function promotedFrom()
    {
        return $this->belongsTo(Release::class, 'promoted_from_id');
    }

    public function promotions()
    {
        return $this->hasMany(Release::class, 'promoted_from_id');
    }

    public function events()
    {
        return $this->hasMany(ReleaseEvent::class);
    }
}
