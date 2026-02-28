<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstnames',
        'surname',
        'gender',
        'address',
        'nationalid',
        'institution_id',
        'membership_id',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(StudentPhone::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function nextOfKins(): HasMany
    {
        return $this->hasMany(StudentNextOfKin::class);
    }

    public function disabilities(): BelongsToMany
    {
        return $this->belongsToMany(Disability::class, 'student_disabilities')
            ->withTimestamps();
    }

    public function dietaries(): BelongsToMany
    {
        return $this->belongsToMany(Dietary::class, 'student_dietaries')
            ->withTimestamps();
    }

    public function chronicConditions(): BelongsToMany
    {
        return $this->belongsToMany(ChronicCondition::class, 'student_chronic_conditions')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
