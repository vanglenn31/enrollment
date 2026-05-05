<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    protected $fillable = [
        'school_year',
        'semester',
        'start_date',
        'end_date',
        'status',
        'is_enrollment_open',
    ];

    protected $casts = [
        'start_date'         => 'date',
        'end_date'           => 'date',
        'is_enrollment_open' => 'boolean',
    ];

    // ──────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────

    public function studentEnrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    // ──────────────────────────────────────────────
    //  Scopes
    // ──────────────────────────────────────────────

    /** The one currently running term. */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /** Terms that have already concluded. */
    public function scopeEnded($query)
    {
        return $query->where('status', 'ended');
    }

    /** Terms scheduled for the future. */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    // ──────────────────────────────────────────────
    //  Accessors / Helpers
    // ──────────────────────────────────────────────

    /**
     * Human-friendly label: "1st Semester 2025–2026"
     */
    public function getLabelAttribute(): string
    {
        $semesterLabel = match ($this->semester) {
            '1st'    => '1st Semester',
            '2nd'    => '2nd Semester',
            'summer' => 'Summer Term',
            default  => $this->semester,
        };

        return "{$semesterLabel} {$this->school_year}";
    }

    /**
     * True when today is past end_date (or status is already 'ended').
     */
    public function isExpired(): bool
    {
        if ($this->status === 'ended') {
            return true;
        }

        return $this->end_date && Carbon::today()->isAfter($this->end_date);
    }

    /**
     * Days remaining until end_date. Returns null if no end_date set.
     * Negative value means already past end_date.
     */
    public function daysRemaining(): ?int
    {
        if (! $this->end_date) {
            return null;
        }

        $diff = Carbon::today()->diffInDays($this->end_date, false);

        return (int) $diff;
    }

    /**
     * Badge colour hint for the UI (Tailwind classes).
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active'   => 'bg-green-100 text-green-700',
            'ended'    => 'bg-red-100 text-red-700',
            'upcoming' => 'bg-yellow-100 text-yellow-700',
            default    => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Activate this term and demote any currently active term to 'ended'.
     * Sets is_enrollment_open to false by default.
     */
    public function activate(): void
    {
        // End the current active term first
        static::where('status', 'active')->update(['status' => 'ended', 'is_enrollment_open' => false]);

        $this->update(['status' => 'active']);
    }

    /**
     * Toggle enrollment open/closed for this term.
     */
    public function toggleEnrollment(): void
    {
        $this->update(['is_enrollment_open' => ! $this->is_enrollment_open]);
    }
}