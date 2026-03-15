<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentEditHistory extends Model
{
    protected $fillable = [
        'comment_id', // BIGINT UNSIGNED NOT NULL
        'old_content' // TEXT NOT NULL
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}