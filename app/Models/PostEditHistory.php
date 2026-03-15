<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostEditHistory extends Model
{
    protected $fillable = [
        'post_id',       // BIGINT UNSIGNED NOT NULL
        'old_content',   // TEXT NULL
        'old_media_path',// VARCHAR(255) NULL
        'old_media_type' // ENUM('IMAGE','VIDEO','NONE') NOT NULL DEFAULT 'NONE'
    ];

    const MEDIA_TYPE_IMAGE = 'IMAGE';
    const MEDIA_TYPE_VIDEO = 'VIDEO';
    const MEDIA_TYPE_NONE = 'NONE';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}