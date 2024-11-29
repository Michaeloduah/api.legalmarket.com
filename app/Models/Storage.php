<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $fillable = [
        'uuid',
        'user_uuid',
        'slug',
        'path',
        'mimeType',
    ];

    protected $hidden = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
