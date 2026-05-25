<?php

namespace App\Models;

use Database\Factories\ArsipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    /** @use HasFactory<ArsipFactory> */
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'nomor_arsip',
        'tgl_dilegalkan',
        'judul',
        'jenis_arsip_id',
        'bidang_id',
        'file_path',
        'file_size',
        'file_type',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'tgl_dilegalkan' => 'date',
        ];
    }

    public function jenisArsip()
    {
        return $this->belongsTo(JenisArsip::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
