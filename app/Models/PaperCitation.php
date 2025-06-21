<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperCitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'published_paper_id',
        'user_id',
    ];

    public function paper()
    {
        return $this->belongsTo(PublishedPaper::class, 'published_paper_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
