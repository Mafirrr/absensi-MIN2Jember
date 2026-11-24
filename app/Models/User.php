<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jenis_guru',
        'mata_pelajaran',
        'nip',
        'phone',
        'gender',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isTU(): bool
    {
        return $this->role === 'tu';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isGuruMapel(): bool
    {
        return $this->isGuru() && $this->jenis_guru === 'guru_mapel';
    }

    public function isGuruKelas(): bool
    {
        return $this->isGuru() && ($this->jenis_guru === 'guru_kelas' || empty($this->jenis_guru));
    }

    public function getJabatanLabelAttribute(): string
    {
        if ($this->isTU()) {
            return 'Tata Usaha';
        }
        if ($this->isGuruMapel()) {
            return 'Guru Mapel ' . ($this->mata_pelajaran ?: '');
        }
        if ($this->kelasWali->isNotEmpty()) {
            return 'Wali Kelas ' . $this->kelasWali->first()->nama_kelas;
        }
        return 'Guru Kelas';
    }

    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function kelasAjar(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas', 'user_id', 'kelas_id')
            ->withPivot('mata_pelajaran')
            ->withTimestamps();
    }

    /**
     * Get IDs of classes this user can access
     */
    public function getAccessibleKelasIds(): array
    {
        if ($this->isTU()) {
            return Kelas::pluck('id')->toArray();
        }

        $waliIds = $this->kelasWali()->pluck('id')->toArray();
        $ajarIds = $this->kelasAjar()->pluck('kelas.id')->toArray();

        return array_values(array_unique(array_merge($waliIds, $ajarIds)));
    }

    /**
     * Get accessible classes query
     */
    public function accessibleKelas()
    {
        if ($this->isTU()) {
            return Kelas::query();
        }

        $ids = $this->getAccessibleKelasIds();
        return Kelas::whereIn('id', $ids);
    }
}
