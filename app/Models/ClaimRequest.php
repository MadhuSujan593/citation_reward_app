<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'citer_paper_title',
        'paper_link',
        'pdf_document',
        'referenced_paper_id',
        'reference_id',
        'status',
        'admin_notes',
        'claim_amount',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'claim_amount' => 'decimal:2',
        'reviewed_at' => 'datetime'
    ];

    protected $appends = ['formatted_claim_amount', 'status_badge_class'];

    /**
     * Relationship: Claim request belongs to a user (citer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Referenced paper that was cited
     */
    public function referencedPaper()
    {
        return $this->belongsTo(PublishedPaper::class, 'referenced_paper_id');
    }

    /**
     * Relationship: Admin who reviewed the claim
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get formatted claim amount
     */
    public function getFormattedClaimAmountAttribute()
    {
        return '₹' . number_format($this->claim_amount, 2);
    }

    /**
     * Get status badge CSS class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'approved' => 'bg-green-100 text-green-800 border-green-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Check if claim can be approved
     */
    public function canBeApproved()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if claim can be rejected
     */
    public function canBeRejected()
    {
        return $this->status === 'pending';
    }
}
