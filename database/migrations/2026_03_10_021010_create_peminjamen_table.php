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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();

            // Nomor transaksi unik, e.g. PNJ-20250310-001
            $table->string('nomor_pinjam')->unique();

            // Who is borrowing
            $table->foreignId('peminjam_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            // Which equipment
            $table->foreignId('alat_id')
                  ->constrained('alats')
                  ->restrictOnDelete();

            // Petugas who approved (nullable until approved)
            $table->foreignId('petugas_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->unsignedInteger('jumlah')->default(1);  // quantity borrowed

            $table->date('tanggal_pinjam');                 // borrow start date
            $table->date('tanggal_kembali_rencana');        // planned return date

            $table->decimal('total_biaya', 12, 2)
                  ->default(0);                             // harga_sewa × jumlah × durasi

            $table->text('tujuan_peminjaman')->nullable();  // why they need it

            /**
             * Status flow:
             *   menunggu → disetujui → dipinjam → dikembalikan
             *                       ↘ ditolak
             */
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'dipinjam',
                'dikembalikan',
            ])->default('menunggu');

            $table->text('catatan_petugas')->nullable();    // notes when approving / rejecting
            $table->timestamp('disetujui_at')->nullable();  // when approved/rejected

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};