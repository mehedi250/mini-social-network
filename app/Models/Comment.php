<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',   // BIGINT UNSIGNED NOT NULL
        'post_id',   // BIGINT UNSIGNED NOT NULL
        'content',   // TEXT NOT NULL
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function editHistories()
    {
        return $this->hasMany(CommentEditHistory::class);
    }
}