<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',             // BIGINT UNSIGNED NOT NULL
        'profile_image',       // VARCHAR(255) NULL
        'cover_image',         // VARCHAR(255) NULL
        'bio',                 // VARCHAR(120) NULL
        'profession',          // VARCHAR(255) NULL
        'company',             // VARCHAR(255) NULL
        'education',           // VARCHAR(255) NULL
        'date_of_birth',       // DATE NULL
        'gender',              // ENUM('MALE','FEMALE','OTHER','PREFER_NOT_TO_SAY') NOT NULL DEFAULT 'PREFER_NOT_TO_SAY'
        'relationship_status', // VARCHAR(255) NULL
        'home_city',           // VARCHAR(255) NULL
        'current_city',        // VARCHAR(255) NULL
        'website',             // VARCHAR(255) NULL
    ];

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';
    const GENDER_OTHER = 'OTHER';
    const GENDER_PREFER_NOT_TO_SAY = 'PREFER_NOT_TO_SAY';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}