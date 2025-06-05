<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nama', 'nim', 'email', 'telepon', 'alamat', 'tanggal_lahir', 'jurusan', 'foto', 'status', 'angkatan', 'jenis_kelamin', 'agama'];
}