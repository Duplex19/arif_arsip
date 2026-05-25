<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->index();
            $table->date('tgl_dilegalkan')->index();
            $table->text('judul');
            $table->foreignId('jenis_arsip_id')->constrained('jenis_arsip')->restrictOnDelete();
            $table->foreignId('bidang_id')->constrained('bidang')->restrictOnDelete();
            $table->string('file_path');
            $table->unsignedInteger('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['bidang_id', 'tgl_dilegalkan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
