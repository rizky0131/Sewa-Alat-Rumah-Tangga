<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')
                  ->constrained('kategoris')
                  ->restrictOnDelete();                      // cannot delete kategori if alat exists

            $table->string('kode')->unique();               // e.g. ALT-001
            $table->string('nama');                         // e.g. Vacuum Cleaner Portable
            $table->text('deskripsi')->nullable();
            $table->string('merk')->nullable();             // brand
            $table->string('foto')->nullable();             // file path

            $table->unsignedInteger('stok_total');          // total units owned
            $table->unsignedInteger('stok_tersedia');       // units currently available

            $table->decimal('harga_sewa_per_hari', 10, 2); // rental price per day
            $table->decimal('denda_per_hari', 10, 2)
                  ->default(0);                             // late return fine per day

            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'perbaikan'])
                  ->default('baik');

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();                          // safe delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};