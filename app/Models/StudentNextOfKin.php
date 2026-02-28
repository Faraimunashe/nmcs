<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentNextOfKin extends Model
{
    use HasFactory;

    protected $table = 'student_next_of_kins';

    protected $fillable = [
        'student_id',
        'relationship',
        'fullname',
        'phone',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
