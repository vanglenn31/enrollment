<?php

namespace App\Models;
use App\Http\Controllers\PaymentController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'student_enrollment_id',
        'amount',
        'payment_date',
        'payment_status',
        'payment_method',
        'payment_type',       // 'downpayment' | 'tuition'
        'reference_number',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function studentEnrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class);
    }

    public function paymentRequests(): HasMany
    {
        return $this->hasMany(PaymentRequest::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeDownpayments($query)
    {
        return $query->where('payment_type', 'downpayment');
    }

    public function scopeTuition($query)
    {
        return $query->where('payment_type', 'tuition');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isDownpayment(): bool
    {
        return $this->payment_type === 'downpayment';
    }
}