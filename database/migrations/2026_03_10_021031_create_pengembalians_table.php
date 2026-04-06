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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();

            // Link back to the borrow record (1 peminjaman → 1 pengembalian)
            $table->foreignId('peminjaman_id')
                  ->unique()
                  ->constrained('peminjamans')
                  ->restrictOnDelete();

            // Petugas who recorded the return
            $table->foreignId('petugas_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->date('tanggal_kembali_aktual');         // actual return date

            // Days overdue (computed: aktual - rencana, 0 if on time)
            $table->unsignedInteger('keterlambatan_hari')->default(0);

            $table->decimal('denda', 12, 2)->default(0);    // calculated fine

            $table->enum('kondisi_kembali', [
                'baik',
                'rusak_ringan',
                'rusak_sedang',
                'rusak_berat',
                'hilang',
            ])->default('baik');

            $table->decimal('biaya_kerusakan', 12, 2)
                  ->default(0);                             // extra charge for damage / loss

            $table->decimal('total_tagihan', 12, 2)
                  ->default(0);                             // denda + biaya_kerusakan

            $table->text('catatan')->nullable();            // petugas notes on return
            $table->string('foto_bukti')->nullable();       // optional proof photo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};