<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'user_id',    // BIGINT UNSIGNED NOT NULL
        'content',    // TEXT NULL
        'media_path', // VARCHAR(255) NULL
        'media_type', // ENUM('IMAGE','VIDEO','NONE') NOT NULL DEFAULT 'NONE'
        'privacy',    // ENUM('PUBLIC','FOLLOWERS','PRIVATE') NOT NULL DEFAULT 'PUBLIC'
    ];

    const MEDIA_TYPE_IMAGE = 'IMAGE';
    const MEDIA_TYPE_VIDEO = 'VIDEO';
    const MEDIA_TYPE_NONE = 'NONE';

    const PRIVACY_PUBLIC = 'PUBLIC';
    const PRIVACY_FOLLOWERS = 'FOLLOWERS';
    const PRIVACY_PRIVATE = 'PRIVATE';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function editHistories()
    {
        return $this->hasMany(PostEditHistory::class);
    }
}