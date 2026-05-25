<?php

namespace App\Models;

use Database\Factories\BidangFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    /** @use HasFactory<BidangFactory> */
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = ['nama_bidang'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }
}
