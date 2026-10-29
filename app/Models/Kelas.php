<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'wali_kelas_id',
        'tahun_ajaran',
    ];

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function guruPengampu(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'guru_kelas', 'kelas_id', 'user_id')
            ->withPivot('mata_pelajaran')
            ->withTimestamps();
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id')->orderBy('nama_lengkap', 'asc');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'kelas_id');
    }
}
