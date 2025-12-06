<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the schedules for the user (if role is dosen).
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the bookings for the user (if role is mahasiswa).
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the dosen assigned to this mahasiswa.
     */
    public function assignedDosens()
    {
        return $this->belongsToMany(User::class, 'assignments', 'mahasiswa_id', 'dosen_id');
    }

    /**
     * Get the mahasiswa assigned to this dosen.
     */
    public function assignedMahasiswas()
    {
        return $this->belongsToMany(User::class, 'assignments', 'dosen_id', 'mahasiswa_id');
    }

    /**
     * Get counseling requests sent by mahasiswa.
     */
    public function counselingRequestsSent()
    {
        return $this->hasMany(CounselingRequest::class, 'mahasiswa_id');
    }

    /**
     * Get counseling requests received by dosen.
     */
    public function counselingRequestsReceived()
    {
        return $this->hasMany(CounselingRequest::class, 'dosen_id');
    }
}
