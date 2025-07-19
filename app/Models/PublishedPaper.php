<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PublishedPaper extends Model
{
    use HasFactory;

    protected $appends = ['author_name','is_paper_cited_by_current_user', 'has_pending_claim'];

    protected $fillable = [
        'title',
        'mla',
        'apa',
        'chicago',
        'harvard',
        'vancouver',
        'user_id',
        'doi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAuthorNameAttribute()
    {
        return $this->user 
            ? trim($this->user->first_name . ' ' . $this->user->last_name) 
            : null;
    }

    public function citers()
    {
        return $this->belongsToMany(User::class, 'paper_citations', 'published_paper_id', 'user_id')
                    ->withTimestamps();
    }

    public function getIsPaperCitedByCurrentUserAttribute()
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $this->citers->contains('id', $user->id);
    }

    public function getHasPendingClaimAttribute()
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return \App\Models\ClaimRequest::where('user_id', $user->id)
            ->where('referenced_paper_id', $this->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }
}
