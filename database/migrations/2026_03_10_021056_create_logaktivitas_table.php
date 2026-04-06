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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();

            // The user who performed the action (nullable: system events)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            /**
             * Event categories — mirrors the feature table:
             *   login / logout / crud_user / crud_alat / crud_kategori /
             *   crud_peminjaman / crud_pengembalian /
             *   setujui_peminjaman / tolak_peminjaman / pantau_pengembalian /
             *   cetak_laporan
             */
            $table->string('aksi');                         // e.g. 'setujui_peminjaman'
            $table->string('modul');                        // e.g. 'Peminjaman'
            $table->text('deskripsi')->nullable();          // human-readable detail
            $table->json('data_lama')->nullable();          // before state (for CRUD)
            $table->json('data_baru')->nullable();          // after state  (for CRUD)

            // Polymorphic subject (optional but useful for deep auditing)
            $table->nullableMorphs('subject');              // subject_id + subject_type

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('created_at')->useCurrent();  // log only needs created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};