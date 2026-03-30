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
    ];
}
