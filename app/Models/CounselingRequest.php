<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselingRequest extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'date',
        'start_time',
        'end_time',
        'file_path',
        'message',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
