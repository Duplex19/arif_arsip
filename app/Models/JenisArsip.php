<?php

namespace App\Models;

use Database\Factories\JenisArsipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisArsip extends Model
{
    /** @use HasFactory<JenisArsipFactory> */
    use HasFactory;

    protected $table = 'jenis_arsip';

    protected $fillable = ['nama_jenis', 'kode_jenis'];

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }
}
