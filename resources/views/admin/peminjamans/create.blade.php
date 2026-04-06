<x-admin-layout title="Buat Peminjaman" breadcrumb="Buat Peminjaman">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Buat Peminjaman Manual</h1>
            <p class="page-sub">Admin membuat transaksi peminjaman langsung atas nama peminjam.</p>
        </div>
        <a href="{{ route('admin.peminjamans.index') }}" class="btn btn-ghost">← Kembali</a>
    </x-slot>

    <style>
        .create-layout { display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start; }
        .fcard { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;margin-bottom:1.2rem;overflow:hidden; }
        .fcard-head { padding:1.1rem 1.4rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem; }
        .fch-icon { width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,0.15);color:var(--accent-l);display:flex;align-items:center;justify-content:center;font-size:0.95rem; }
        .fch-title { font-size:0.88rem;font-weight:700;color:var(--cream); }
        .fcard-body { padding:1.4rem; }

        /* Peminjam select with search */
        .user-select-card {
            display:flex;align-items:center;gap:0.75rem;padding:0.9rem;
            background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:7px;
            cursor:pointer;transition:border-color 0.15s;
        }
        .user-select-card:hover { border-color:rgba(37,99,235,0.4); }
        .user-select-card.selected { border-color:var(--accent);background:rgba(37,99,235,0.08); }
        .usc-av { width:34px;height:34px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;color:white;background:linear-gradient(135deg,#10B981,#06B6D4); }

        /* Alat picker */
        .alat-picker-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:0.6rem; }
        .alat-pick-option { position:relative; }
        .alat-pick-option input { position:absolute;opacity:0;width:0;height:0; }
        .alat-pick-label {
            display:flex;align-items:center;gap:0.65rem;padding:0.75rem;
            border-radius:7px;cursor:pointer;
            background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.08);
            transition:all 0.15s;
        }
        .alat-pick-label:hover { border-color:rgba(255,255,255,0.2); }
        .alat-pick-option input:checked + .alat-pick-label { border-color:var(--accent);background:rgba(37,99,235,0.1); }
        .apk-thumb { width:34px;height:34px;border-radius:7px;overflow:hidden;flex-shrink:0;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;font-size:0.9rem; }
        .apk-thumb img { width:100%;height:100%;object-fit:cover; }
        .apk-name  { font-size:0.78rem;font-weight:700;color:var(--silver);line-height:1.3; }
        .apk-meta  { font-size:0.65rem;color:var(--mist); }
        .apk-stok  { margin-left:auto;font-size:0.7rem;font-weight:700;flex-shrink:0; }

        /* Date range picker */
        .date-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }

        /* Status selector */
        .status-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:0.6rem; }
        .status-opt { position:relative; }
        .status-opt input { position:absolute;opacity:0;width:0;height:0; }
        .status-lbl {
            display:flex;flex-direction:column;align-items:center;gap:0.35rem;
            padding:0.8rem 0.4rem;border-radius:7px;cursor:pointer;text-align:center;
            background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.08);transition:all 0.15s;
        }
        .status-lbl:hover { border-color:rgba(255,255,255,0.2); }
        .status-opt input:checked + .status-lbl { border-color:var(--accent);background:rgba(37,99,235,0.1); }
        .slbl-em  { font-size:1.2rem; }
        .slbl-txt { font-size:0.72rem;font-weight:700;color:var(--silver); }
        .slbl-hint { font-size:0.6rem;color:var(--mist); }

        /* Summary box */
        .summary-box { background:var(--ink-60);border:1px solid rgba(255,255,255,0.1);border-radius:8px;overflow:hidden; }
        .sb-head { padding:0.8rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.08);font-size:0.78rem;font-weight:700;color:var(--cream); }
        .sb-row { display:flex;justify-content:space-between;align-items:center;padding:0.6rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.05); }
        .sb-row:last-child { border-bottom:none; }
        .sb-key { font-size:0.75rem;color:var(--mist); }
        .sb-val { font-size:0.8rem;font-weight:700;color:var(--silver); }
        .sb-total { font-size:1.1rem;font-weight:800;color:var(--jade); }

        /* Price input */
        .price-wrap { position:relative; }
        .price-prefix { position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);color:var(--mist);font-size:0.78rem;pointer-events:none; }
        .price-wrap .form-input { padding-left:2.8rem; }

        @media(max-width:1000px) { .create-layout { grid-template-columns:1fr; } .alat-picker-grid { grid-template-columns:1fr; } }
    </style>

    <form method="POST" action="{{ route('admin.peminjamans.store') }}" id="createPinjamanForm">
        @csrf

        <div class="create-layout">
            {{-- MAIN --}}
            <div>

                {{-- Peminjam --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">👤</div>
                        <span class="fch-title">Pilih Peminjam</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">User Peminjam <span style="color:var(--danger)">*</span></label>
                            <select name="peminjam_id" id="peminjamSelect"
                                    class="form-select {{ $errors->has('peminjam_id') ? 'border-danger':'' }}"
                                    onchange="updateSummary()">
                                <option value="">— Pilih peminjam —</option>
                                @foreach($peminjams as $u)
                                    <option value="{{ $u->id }}" {{ old('peminjam_id')==$u->id ? 'selected':'' }}>
                                        {{ $u->name }} — {{ $u->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peminjam_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Alat --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🛠</div>
                        <span class="fch-title">Pilih Alat</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Alat <span style="color:var(--danger)">*</span></label>
                            <select name="alat_id" id="alatSelect"
                                    class="form-select {{ $errors->has('alat_id') ? 'border-danger':'' }}"
                                    onchange="onAlatChange(this)">
                                <option value="">— Pilih alat —</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}"
                                            data-harga="{{ $alat->harga_sewa_per_hari }}"
                                            data-stok="{{ $alat->stok_tersedia }}"
                                            {{ old('alat_id')==$alat->id ? 'selected':'' }}>
                                        {{ $alat->kategori->ikon ?? '' }} {{ $alat->nama }}
                                        ({{ $alat->kode }}) — Stok: {{ $alat->stok_tersedia }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alat_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Jumlah Unit <span style="color:var(--danger)">*</span></label>
                                <input type="number" name="jumlah" id="jumlahInput"
                                       value="{{ old('jumlah', 1) }}" min="1"
                                       class="form-input {{ $errors->has('jumlah') ? 'border-danger':'' }}"
                                       oninput="updateSummary()">
                                <p class="form-hint" id="stokHint">Stok tersedia: —</p>
                                @error('jumlah') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tujuan Peminjaman</label>
                                <input type="text" name="tujuan_peminjaman"
                                       value="{{ old('tujuan_peminjaman') }}"
                                       class="form-input" placeholder="cth: Perbaikan rumah, acara, dll">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📅</div>
                        <span class="fch-title">Tanggal Peminjaman</span>
                    </div>
                    <div class="fcard-body">
                        <div class="date-grid">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Pinjam <span style="color:var(--danger)">*</span></label>
                                <input type="date" name="tanggal_pinjam" id="tglPinjam"
                                       value="{{ old('tanggal_pinjam', now()->toDateString()) }}"
                                       class="form-input {{ $errors->has('tanggal_pinjam') ? 'border-danger':'' }}"
                                       oninput="updateSummary()">
                                @error('tanggal_pinjam') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Kembali <span style="color:var(--danger)">*</span></label>
                                <input type="date" name="tanggal_kembali_rencana" id="tglKembali"
                                       value="{{ old('tanggal_kembali_rencana', now()->addDays(3)->toDateString()) }}"
                                       class="form-input {{ $errors->has('tanggal_kembali_rencana') ? 'border-danger':'' }}"
                                       oninput="updateSummary()">
                                @error('tanggal_kembali_rencana') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status & catatan --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">⚙️</div>
                        <span class="fch-title">Status Awal & Catatan</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Status Awal <span style="color:var(--danger)">*</span></label>
                            <div class="status-grid">
                                @foreach(['menunggu'=>['⏳','Menunggu','Perlu disetujui dulu'],'disetujui'=>['✓','Disetujui','Langsung disetujui'],'dipinjam'=>['📦','Dipinjam','Stok langsung dikurangi']] as $val=>[$em,$lbl,$hint])
                                    <div class="status-opt">
                                        <input type="radio" name="status" id="st_{{ $val }}" value="{{ $val }}"
                                               {{ old('status','menunggu')==$val ? 'checked':'' }}>
                                        <label for="st_{{ $val }}" class="status-lbl">
                                            <span class="slbl-em">{{ $em }}</span>
                                            <span class="slbl-txt">{{ $lbl }}</span>
                                            <span class="slbl-hint">{{ $hint }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('status') <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group" style="margin:0">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan_petugas" class="form-textarea" rows="2"
                                      placeholder="Catatan admin atau keterangan tambahan...">{{ old('catatan_petugas') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div>
                {{-- Summary --}}
                <div class="fcard" style="margin-bottom:1rem">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.8rem">🧮</div>
                        <span class="fch-title">Ringkasan Transaksi</span>
                    </div>
                    <div style="padding:0.75rem">
                        <div class="summary-box">
                            <div class="sb-head">Rincian Biaya</div>
                            <div class="sb-row"><span class="sb-key">Alat</span><span class="sb-val" id="sumAlat">—</span></div>
                            <div class="sb-row"><span class="sb-key">Jumlah</span><span class="sb-val" id="sumJumlah">1 unit</span></div>
                            <div class="sb-row"><span class="sb-key">Durasi</span><span class="sb-val" id="sumDurasi">— hari</span></div>
                            <div class="sb-row"><span class="sb-key">Harga/hari</span><span class="sb-val" id="sumHarga">Rp 0</span></div>
                            <div class="sb-row">
                                <span class="sb-key">Total Biaya</span>
                                <span class="sb-total" id="sumTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.7rem;margin-bottom:0.5rem">
                        ＋ Buat Peminjaman
                    </button>
                    <a href="{{ route('admin.peminjamans.index') }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">Batal</a>
                </div>

                {{-- Info --}}
                <div style="background:rgba(212,168,67,0.07);border:1px solid rgba(212,168,67,0.2);border-radius:8px;padding:1.1rem">
                    <div style="font-size:0.75rem;font-weight:700;color:#FCD34D;margin-bottom:0.5rem">💡 Info</div>
                    <div style="font-size:0.73rem;color:var(--silver);line-height:1.6">
                        • Status <strong>Menunggu</strong> — peminjam harus menunggu persetujuan<br>
                        • Status <strong>Disetujui</strong> — stok dikurangi, tinggal serahkan fisik<br>
                        • Status <strong>Dipinjam</strong> — stok langsung dikurangi, alat sudah di tangan peminjam
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const hargaPerAlat = {};
        document.querySelectorAll('#alatSelect option[data-harga]').forEach(opt => {
            hargaPerAlat[opt.value] = { harga: parseFloat(opt.dataset.harga)||0, stok: parseInt(opt.dataset.stok)||0 };
        });

        function onAlatChange(sel) {
            const data = hargaPerAlat[sel.value];
            if (data) {
                document.getElementById('stokHint').textContent = `Stok tersedia: ${data.stok} unit`;
                document.getElementById('jumlahInput').max = data.stok;
            } else {
                document.getElementById('stokHint').textContent = 'Pilih alat terlebih dahulu';
            }
            document.getElementById('sumAlat').textContent = sel.options[sel.selectedIndex]?.text?.split('(')[0]?.trim() || '—';
            updateSummary();
        }

        function updateSummary() {
            const alatId  = document.getElementById('alatSelect').value;
            const jumlah  = parseInt(document.getElementById('jumlahInput').value) || 1;
            const tglP    = document.getElementById('tglPinjam').value;
            const tglK    = document.getElementById('tglKembali').value;
            const data    = hargaPerAlat[alatId];

            let durasi = 0;
            if (tglP && tglK) {
                const d1 = new Date(tglP), d2 = new Date(tglK);
                durasi = Math.max(0, Math.round((d2-d1)/(1000*60*60*24)));
            }

            const harga  = data ? data.harga : 0;
            const total  = harga * jumlah * durasi;

            document.getElementById('sumJumlah').textContent = jumlah + ' unit';
            document.getElementById('sumDurasi').textContent = durasi + ' hari';
            document.getElementById('sumHarga').textContent  = 'Rp ' + harga.toLocaleString('id-ID');
            document.getElementById('sumTotal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Init
        window.addEventListener('DOMContentLoaded', () => {
            const sel = document.getElementById('alatSelect');
            if (sel.value) onAlatChange(sel);
            updateSummary();
        });
    </script>

</x-admin-layout>