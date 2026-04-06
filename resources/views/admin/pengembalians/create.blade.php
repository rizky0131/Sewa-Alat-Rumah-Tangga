<x-admin-layout title="Proses Pengembalian" breadcrumb="Proses Pengembalian">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Proses Pengembalian Alat</h1>
            <p class="page-sub">Catat pengembalian alat, kondisi, dan hitung tagihan otomatis.</p>
        </div>
        <a href="{{ route('admin.pengembalians.index') }}" class="btn btn-ghost">← Kembali</a>
    </x-slot>

    <style>
        .create-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        .fcard {
            background: var(--ink-80);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 8px;
            margin-bottom: 1.2rem;
            overflow: hidden;
        }

        .fcard-head {
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .fch-icon {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            background: rgba(37, 99, 235, 0.15);
            color: var(--accent-l);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .fch-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--cream);
        }

        .fcard-body {
            padding: 1.4rem;
        }

        /* Peminjaman selector */
        .pm-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem;
            border-radius: 7px;
            cursor: pointer;
            margin-bottom: 0.4rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid rgba(255, 255, 255, 0.07);
            transition: all 0.15s;
        }

        .pm-option:hover {
            border-color: rgba(37, 99, 235, 0.4);
            background: rgba(37, 99, 235, 0.06);
        }

        .pm-option.selected {
            border-color: var(--accent);
            background: rgba(37, 99, 235, 0.1);
        }

        .pm-option.late-warning {
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.04);
        }

        .pm-check {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .pm-option.selected .pm-check {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
            font-size: 0.7rem;
        }

        .pm-user {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--silver);
        }

        .pm-alat {
            font-size: 0.72rem;
            color: var(--mist);
        }

        .pm-dates {
            font-size: 0.7rem;
            margin-left: auto;
            text-align: right;
            flex-shrink: 0;
        }

        /* Selected peminjaman detail card */
        .selected-pm-card {
            background: rgba(37, 99, 235, 0.07);
            border: 1px solid rgba(37, 99, 235, 0.25);
            border-radius: 8px;
            padding: 1.1rem;
            margin-bottom: 1rem;
        }

        .spm-row {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .spm-row:last-child {
            border-bottom: none;
        }

        .spm-key {
            font-size: 0.75rem;
            color: var(--mist);
        }

        .spm-val {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--silver);
        }

        /* Kondisi grid */
        .kondisi-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.45rem;
        }

        .kond-opt {
            position: relative;
        }

        .kond-opt input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .kond-lbl {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            padding: 0.7rem 0.2rem;
            border-radius: 7px;
            cursor: pointer;
            text-align: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            transition: all 0.15s;
        }

        .kond-lbl:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .kond-opt input:checked+.kond-lbl.kond-baik {
            border-color: #10B981;
            background: rgba(16, 185, 129, 0.12);
        }

        .kond-opt input:checked+.kond-lbl.kond-rusak_ringan {
            border-color: #D4A843;
            background: rgba(212, 168, 67, 0.12);
        }

        .kond-opt input:checked+.kond-lbl.kond-rusak_sedang {
            border-color: #F97316;
            background: rgba(249, 115, 22, 0.12);
        }

        .kond-opt input:checked+.kond-lbl.kond-rusak_berat {
            border-color: #EF4444;
            background: rgba(239, 68, 68, 0.12);
        }

        .kond-opt input:checked+.kond-lbl.kond-hilang {
            border-color: #8B5CF6;
            background: rgba(139, 92, 246, 0.12);
        }

        .kond-em {
            font-size: 1.3rem;
        }

        .kond-txt {
            font-size: 0.62rem;
            font-weight: 700;
            color: var(--silver);
        }

        /* Photo zone */
        .photo-zone {
            border: 2px dashed rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            background: rgba(255, 255, 255, 0.02);
        }

        .photo-zone:hover {
            border-color: var(--accent);
            background: rgba(37, 99, 235, 0.05);
        }

        .photo-zone input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .photo-preview-wrap {
            display: none;
        }

        .photo-preview-wrap img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 7px;
        }

        /* Price input */
        .price-wrap {
            position: relative;
        }

        .price-prefix {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mist);
            font-size: 0.78rem;
            pointer-events: none;
        }

        .price-wrap .form-input {
            padding-left: 2.8rem;
        }

        /* Tagihan summary card */
        .tagihan-card {
            background: var(--ink-60);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .tc-head {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--cream);
        }

        .tc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.65rem 1.1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .tc-row:last-child {
            border-bottom: none;
            background: rgba(16, 185, 129, 0.07);
        }

        .tc-key {
            font-size: 0.75rem;
            color: var(--mist);
        }

        .tc-val {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--silver);
        }

        .tc-total {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--jade);
        }

        /* Late alert */
        .late-alert {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 7px;
            padding: 0.85rem 1.1rem;
            display: none;
            font-size: 0.8rem;
            color: #FCA5A5;
        }

        .late-alert.visible {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        @media(max-width:1000px) {
            .create-layout {
                grid-template-columns: 1fr;
            }

            .kondisi-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>

    <form method="POST" action="{{ route('admin.pengembalians.store') }}" enctype="multipart/form-data" id="createForm">
        @csrf

        <div class="create-layout">
            {{-- MAIN --}}
            <div>

                {{-- Pilih Peminjaman --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📋</div>
                        <span class="fch-title">Pilih Peminjaman</span>
                    </div>
                    <div class="fcard-body">
                        @if($peminjamansAktif->isEmpty())
                        <div style="text-align:center;padding:1.5rem;color:var(--mist)">
                            <div style="font-size:2rem;margin-bottom:0.5rem">📦</div>
                            Tidak ada peminjaman aktif yang perlu dikembalikan.
                        </div>
                        @else
                        <input type="hidden" name="peminjaman_id" id="peminjamanIdInput" value="{{ old('peminjaman_id', $peminjaman?->id) }}">

                        <div id="pmList">
                            @foreach($peminjamansAktif as $pm)
                            @php $isLate = now()->isAfter($pm->tanggal_kembali_rencana); @endphp
                            <div class="pm-option {{ $isLate ? 'late-warning' : '' }} {{ old('peminjaman_id', $peminjaman?->id) == $pm->id ? 'selected' : '' }}"
                                onclick="selectPeminjaman({{ $pm->id }}, '{{ $pm->nomor_pinjam }}', '{{ $pm->peminjam->name }}', '{{ $pm->alat->nama }}', {{ $pm->jumlah }}, '{{ $pm->tanggal_pinjam->toDateString() }}', '{{ $pm->tanggal_kembali_rencana->toDateString() }}', {{ $pm->alat->denda_per_hari ?? 0 }})">
                                <div class="pm-check">{{ old('peminjaman_id', $peminjaman?->id) == $pm->id ? '✓' : '' }}</div>
                                <div style="flex:1">
                                    <div class="pm-user">{{ $pm->peminjam->name }}</div>
                                    <div class="pm-alat">
                                        🔧 {{ $pm->alat->nama }} · {{ $pm->jumlah }}x
                                        <span style="font-family:monospace;font-size:0.68rem;color:var(--accent-l)">{{ $pm->nomor_pinjam }}</span>
                                    </div>
                                </div>
                                <div class="pm-dates">
                                    <div style="font-size:0.72rem;color:{{ $isLate ? '#FCA5A5' : 'var(--mist)' }};font-weight:{{ $isLate ? '700' : '400' }}">
                                        {{ $isLate ? '⚠ Terlambat' : 'Batas' }}: {{ $pm->tanggal_kembali_rencana->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('peminjaman_id') <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p> @enderror
                        @endif
                    </div>
                </div>

                {{-- Tanggal Kembali --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📅</div>
                        <span class="fch-title">Tanggal Pengembalian</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Tanggal Kembali Aktual <span style="color:var(--danger)">*</span></label>
                            <input type="date" name="tanggal_kembali_aktual" id="tglAktual"
                                value="{{ old('tanggal_kembali_aktual', now()->toDateString()) }}"
                                class="form-input {{ $errors->has('tanggal_kembali_aktual') ? 'border-danger':'' }}"
                                oninput="recalcDenda()">
                            @error('tanggal_kembali_aktual') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Late alert --}}
                        <div class="late-alert" id="lateAlert">
                            <span style="font-size:1.2rem">⚠</span>
                            <span id="lateAlertText"></span>
                        </div>

                        {{-- Detail peminjaman terpilih --}}
                        <div class="selected-pm-card" id="pmDetail" style="{{ old('peminjaman_id', $peminjaman?->id) ? '' : 'display:none' }}">
                            <div class="spm-row">
                                <span class="spm-key">Nomor Transaksi</span>
                                <span class="spm-val" id="detNomor" style="font-family:monospace">—</span>
                            </div>
                            <div class="spm-row">
                                <span class="spm-key">Peminjam</span>
                                <span class="spm-val" id="detPeminjam">—</span>
                            </div>
                            <div class="spm-row">
                                <span class="spm-key">Alat</span>
                                <span class="spm-val" id="detAlat">—</span>
                            </div>
                            <div class="spm-row">
                                <span class="spm-key">Rencana Kembali</span>
                                <span class="spm-val" id="detRencana">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kondisi --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🔍</div>
                        <span class="fch-title">Kondisi Alat Saat Kembali</span>
                    </div>
                    <div class="fcard-body">
                        <div class="kondisi-grid">
                            @foreach(['baik'=>['✅','Baik'],'rusak_ringan'=>['⚠️','Rusak Ringan'],'rusak_sedang'=>['🔶','Rusak Sedang'],'rusak_berat'=>['❌','Rusak Berat'],'hilang'=>['💀','Hilang']] as $val=>[$em,$lbl])
                            <div class="kond-opt">
                                <input type="radio" name="kondisi_kembali" id="kd_{{ $val }}"
                                    value="{{ $val }}"
                                    {{ old('kondisi_kembali','baik')==$val ? 'checked':'' }}
                                    onchange="onKondisiChange('{{ $val }}')">
                                <label for="kd_{{ $val }}" class="kond-lbl kond-{{ $val }}">
                                    <span class="kond-em">{{ $em }}</span>
                                    <span class="kond-txt">{{ $lbl }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('kondisi_kembali') <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p> @enderror

                        {{-- Biaya kerusakan (shown when rusak) --}}
                        <div id="biayaKerusakanWrap" style="margin-top:1rem;display:{{ old('kondisi_kembali','baik')!='baik' ? 'block' : 'none' }}">
                            <div style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.2);border-radius:7px;padding:1rem">
                                <div style="font-size:0.78rem;font-weight:700;color:#FCA5A5;margin-bottom:0.75rem">💸 Biaya Kerusakan / Kehilangan</div>
                                <div class="form-group" style="margin:0">
                                    <label class="form-label">Biaya Tambahan</label>
                                    <div class="price-wrap">
                                        <span class="price-prefix">Rp</span>
                                        <input type="number" name="biaya_kerusakan" id="biayaKerusakanInput"
                                            value="{{ old('biaya_kerusakan', 0) }}" min="0" step="1000"
                                            class="form-input" oninput="recalcDenda()">
                                    </div>
                                    <p class="form-hint">Biaya penggantian kerusakan atau kehilangan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan & Foto --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📝</div>
                        <span class="fch-title">Catatan & Bukti Foto</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Catatan Pengembalian</label>
                            <textarea name="catatan" class="form-textarea" rows="3"
                                placeholder="Kondisi alat, keterangan tambahan, atau catatan khusus...">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="form-group" style="margin:0">
                            <label class="form-label">Foto Bukti (opsional)</label>
                            <div class="photo-zone" id="photoZone">
                                <input type="file" name="foto_bukti" accept="image/*" onchange="previewFoto(this)">
                                <div id="photoPlaceholder">
                                    <div style="font-size:1.6rem;margin-bottom:0.4rem">📷</div>
                                    <div style="font-size:0.8rem;color:var(--mist)">Klik atau drag foto bukti</div>
                                    <div style="font-size:0.7rem;color:var(--slate)">JPG, PNG, WEBP · Maks. 3 MB</div>
                                </div>
                                <div class="photo-preview-wrap" id="photoPreview">
                                    <img id="previewImg" src="" alt="Preview">
                                    <button type="button" onclick="removeFoto()"
                                        style="width:100%;margin-top:0.4rem;background:none;border:none;color:var(--mist);cursor:pointer;font-size:0.75rem">✕ Hapus foto</button>
                                </div>
                            </div>
                            @error('foto_bukti') <p class="form-error" style="margin-top:0.4rem">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div>
                {{-- Tagihan preview --}}
                <div class="fcard" style="margin-bottom:1rem">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.8rem">💰</div>
                        <span class="fch-title">Estimasi Tagihan</span>
                    </div>
                    <div style="padding:0.75rem">
                        <div class="tagihan-card">
                            <div class="tc-head">Rincian Tagihan</div>
                            <div class="tc-row">
                                <span class="tc-key">Denda / hari</span>
                                <span class="tc-val" id="tcDendaPerHari">Rp 0</span>
                            </div>
                            <div class="tc-row">
                                <span class="tc-key">Keterlambatan</span>
                                <span class="tc-val" id="tcTerlambat" style="color:var(--jade)">0 hari</span>
                            </div>
                            <div class="tc-row">
                                <span class="tc-key">Total Denda</span>
                                <span class="tc-val" id="tcDenda" style="color:#FCA5A5">Rp 0</span>
                            </div>
                            <div class="tc-row">
                                <span class="tc-key">Biaya Kerusakan</span>
                                <span class="tc-val" id="tcKerusakan">Rp 0</span>
                            </div>
                            <div class="tc-row">
                                <span class="tc-key">Total Tagihan</span>
                                <span class="tc-total" id="tcTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                        style="width:100%;justify-content:center;padding:0.75rem;margin-bottom:0.5rem">
                        ↩ Proses Pengembalian
                    </button>
                    <a href="{{ route('admin.pengembalians.index') }}"
                        class="btn btn-ghost" style="width:100%;justify-content:center">Batal</a>
                </div>

                {{-- Checklist --}}
                <div style="background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.18);border-radius:8px;padding:1rem">
                    <div style="font-size:0.72rem;font-weight:700;color:var(--accent-l);margin-bottom:0.5rem">📋 Checklist</div>
                    <div style="font-size:0.75rem;line-height:2">
                        <div id="chk-pm" style="color:var(--slate)">○ Pilih peminjaman</div>
                        <div id="chk-tgl" style="color:var(--slate)">○ Tanggal kembali</div>
                        <div id="chk-kondisi" style="color:var(--jade)">✓ Kondisi alat</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // State
        let selectedPm = null;

        @if($peminjaman)
        // Pre-select from URL param
        window.addEventListener('DOMContentLoaded', () => {
            selectPeminjaman({
                    {
                        $peminjaman - > id
                    }
                },
                '{{ $peminjaman->nomor_pinjam }}',
                '{{ $peminjaman->peminjam->name }}',
                '{{ $peminjaman->alat->nama }}', {
                    {
                        $peminjaman - > jumlah
                    }
                },
                '{{ $peminjaman->tanggal_pinjam->toDateString() }}',
                '{{ $peminjaman->tanggal_kembali_rencana->toDateString() }}', {
                    {
                        $peminjaman - > alat - > denda_per_hari
                    }
                }
            );
        });
        @endif

        function selectPeminjaman(id, nomor, peminjam, alat, jumlah, tglPinjam, tglRencana, dendaPerHari) {
            selectedPm = {
                id,
                nomor,
                peminjam,
                alat,
                jumlah: Number(jumlah) || 0,
                tglRencana,
                dendaPerHari: Number(dendaPerHari) || 0
            };

            document.getElementById('peminjamanIdInput').value = id;

            // Visual selection
            document.querySelectorAll('.pm-option').forEach(el => {
                el.classList.remove('selected');
                el.querySelector('.pm-check').textContent = '';
            });
            const sel = [...document.querySelectorAll('.pm-option')].find(el =>
                el.onclick.toString().includes(`selectPeminjaman(${id},`)
            );
            if (sel) {
                sel.classList.add('selected');
                sel.querySelector('.pm-check').textContent = '✓';
            }

            // Update detail card
            document.getElementById('detNomor').textContent = nomor;
            document.getElementById('detPeminjam').textContent = peminjam;
            document.getElementById('detAlat').textContent = alat + ' × ' + jumlah;
            document.getElementById('detRencana').textContent = new Date(tglRencana).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            document.getElementById('pmDetail').style.display = 'block';

            // Update denda per hari in summary
            document.getElementById('tcDendaPerHari').textContent = 'Rp ' + (dendaPerHari * jumlah).toLocaleString('id-ID');

            // Checklist
            document.getElementById('chk-pm').textContent = '✓ Pilih peminjaman';
            document.getElementById('chk-pm').style.color = 'var(--jade)';

            recalcDenda();
        }

        function recalcDenda() {
            if (!selectedPm) return;

            const tglAktual = document.getElementById('tglAktual').value;
            if (!tglAktual) return;

            const aktual = new Date(tglAktual);
            const rencana = new Date(selectedPm.tglRencana);
            const diffMs = aktual - rencana;
            const terlambat = diffMs > 0 ? Math.round(diffMs / (1000 * 60 * 60 * 24)) : 0;

            const denda = (terlambat || 0) * (selectedPm.dendaPerHari || 0) * (selectedPm.jumlah || 0);
            const biayaKerusakan = parseInt(document.getElementById('biayaKerusakanInput')?.value) || 0;
            const total = denda + biayaKerusakan;

            // Update summary
            document.getElementById('tcTerlambat').textContent = terlambat + ' hari';
            document.getElementById('tcTerlambat').style.color = terlambat > 0 ? '#FCA5A5' : 'var(--jade)';
            document.getElementById('tcDenda').textContent = 'Rp ' + denda.toLocaleString('id-ID');
            document.getElementById('tcKerusakan').textContent = 'Rp ' + biayaKerusakan.toLocaleString('id-ID');
            document.getElementById('tcTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');

            // Late alert
            const alert = document.getElementById('lateAlert');
            const alertText = document.getElementById('lateAlertText');
            if (terlambat > 0) {
                alertText.textContent = `Terlambat ${terlambat} hari. Denda: Rp ${denda.toLocaleString('id-ID')}`;
                alert.classList.add('visible');
            } else {
                alert.classList.remove('visible');
            }

            // Checklist
            document.getElementById('chk-tgl').textContent = '✓ Tanggal kembali';
            document.getElementById('chk-tgl').style.color = 'var(--jade)';
        }

        function onKondisiChange(val) {
            const biayaWrap = document.getElementById('biayaKerusakanWrap');
            biayaWrap.style.display = val !== 'baik' ? 'block' : 'none';
            recalcDenda();

            // Checklist
            document.getElementById('chk-kondisi').textContent = '✓ Kondisi alat';
            document.getElementById('chk-kondisi').style.color = 'var(--jade)';
        }

        // Photo
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('photoPlaceholder').style.display = 'none';
                    document.getElementById('photoPreview').style.display = 'block';
                    document.getElementById('previewImg').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeFoto() {
            document.querySelector('input[name="foto_bukti"]').value = '';
            document.getElementById('photoPlaceholder').style.display = 'block';
            document.getElementById('photoPreview').style.display = 'none';
        }

        // Drag & drop
        const zone = document.getElementById('photoZone');
        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.style.borderColor = 'var(--accent)';
        });
        zone.addEventListener('dragleave', () => {
            zone.style.borderColor = '';
        });
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.style.borderColor = '';
            if (e.dataTransfer.files[0]) {
                document.querySelector('input[name="foto_bukti"]').files = e.dataTransfer.files;
                previewFoto(document.querySelector('input[name="foto_bukti"]'));
            }
        });
    </script>

</x-admin-layout>