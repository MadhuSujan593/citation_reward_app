<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishedPaper extends Model
{
    use HasFactory;

    protected $appends = ['author_name'];

    protected $fillable = [
        'author_id',
        'title',
        'mla',
        'apa',
        'chicago',
        'harvard',
        'vancouver',
        'user_id'
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getAuthorNameAttribute()
    {
        return $this->user 
            ? trim($this->user->first_name . ' ' . $this->user->last_name) 
            : null;
    }
}
