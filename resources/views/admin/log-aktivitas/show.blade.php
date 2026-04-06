<x-admin-layout title="Detail Log" breadcrumb="Detail Log">

    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                <h1 class="page-heading" style="font-size:1.35rem">Log #{{ $logAktivita->id }}</h1>
                <span class="aksi-badge aksi-{{ $logAktivita->aksi }}">{{ $logAktivita->aksi }}</span>
                <span class="modul-chip">{{ $logAktivita->modul }}</span>
            </div>
            <p class="page-sub">{{ $logAktivita->created_at->format('d M Y, H:i:s') }} · {{ $logAktivita->created_at->diffForHumans() }}</p>
        </div>
        <div style="display:flex;gap:0.4rem">
            @if($prev)
                <a href="{{ route('admin.log-aktivitas.show', $prev) }}" class="btn btn-ghost btn-sm">‹ Prev</a>
            @endif
            @if($next)
                <a href="{{ route('admin.log-aktivitas.show', $next) }}" class="btn btn-ghost btn-sm">Next ›</a>
            @endif
            <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <style>
        .aksi-badge { display:inline-flex;align-items:center;padding:0.22rem 0.65rem;border-radius:100px;font-size:0.68rem;font-weight:700;font-family:monospace; }
        .aksi-login,.aksi-logout          { background:rgba(59,130,246,0.12);color:#93C5FD;border:1px solid rgba(59,130,246,0.2); }
        .aksi-crud_user                   { background:rgba(139,92,246,0.12);color:#C4B5FD;border:1px solid rgba(139,92,246,0.2); }
        .aksi-crud_alat,.aksi-crud_kategori { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.2); }
        .aksi-crud_peminjaman,.aksi-crud_pengembalian { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.2); }
        .aksi-setujui_peminjaman          { background:rgba(16,185,129,0.15);color:#34D399;border:1px solid rgba(16,185,129,0.3); }
        .aksi-tolak_peminjaman            { background:rgba(239,68,68,0.12);color:#FCA5A5;border:1px solid rgba(239,68,68,0.2); }
        .aksi-pantau_pengembalian         { background:rgba(249,115,22,0.12);color:#FDBA74;border:1px solid rgba(249,115,22,0.2); }
        .aksi-cetak_laporan               { background:rgba(100,116,139,0.15);color:var(--silver);border:1px solid rgba(255,255,255,0.12); }
        .modul-chip { font-size:0.7rem;font-weight:700;color:var(--mist);padding:0.2rem 0.6rem;background:rgba(255,255,255,0.05);border-radius:4px; }

        .show-layout { display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start; }

        /* Info card */
        .info-card { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;margin-bottom:1.2rem; }
        .info-card-head { padding:0.9rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.07);font-size:0.82rem;font-weight:700;color:var(--cream); }
        .info-row { display:flex;padding:0.75rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.05); }
        .info-row:last-child { border-bottom:none; }
        .info-key { font-size:0.75rem;color:var(--mist);font-weight:600;width:38%;flex-shrink:0; }
        .info-val { font-size:0.82rem;color:var(--silver);flex:1;word-break:break-all; }

        /* JSON diff viewer */
        .diff-panel { display:grid;grid-template-columns:1fr 1fr;gap:0; }
        .diff-side { flex:1; }
        .diff-side:first-child { border-right:1px solid rgba(255,255,255,0.07); }
        .diff-label { padding:0.65rem 1rem;font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;border-bottom:1px solid rgba(255,255,255,0.07); }
        .diff-label.old { color:#FCA5A5;background:rgba(239,68,68,0.06); }
        .diff-label.new { color:#34D399;background:rgba(16,185,129,0.06); }
        .diff-body { padding:1rem;max-height:340px;overflow-y:auto; }
        .diff-empty { padding:1.5rem;text-align:center;color:var(--slate);font-size:0.78rem; }

        /* JSON renderer */
        .json-tree { font-family:monospace;font-size:0.78rem;line-height:1.8;color:var(--silver); }
        .json-key   { color:#93C5FD; }
        .json-str   { color:#34D399; }
        .json-num   { color:#FCD34D; }
        .json-bool  { color:#FDBA74; }
        .json-null  { color:var(--slate); }

        /* Changed fields highlight */
        .field-changed-old { background:rgba(239,68,68,0.12);border-radius:3px;padding:0.1rem 0.3rem;display:block; }
        .field-changed-new { background:rgba(16,185,129,0.12);border-radius:3px;padding:0.1rem 0.3rem;display:block; }

        /* Subject card */
        .subject-card {
            background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.2);
            border-radius:7px;padding:1rem;text-decoration:none;display:block;
            transition:border-color 0.15s;
        }
        .subject-card:hover { border-color:rgba(37,99,235,0.4); }

        /* User avatar */
        .ua { width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;color:white;flex-shrink:0; }
        .role-admin    { background:linear-gradient(135deg,#7C3AED,#4F46E5); }
        .role-petugas  { background:linear-gradient(135deg,#0D9488,#0891B2); }
        .role-peminjam { background:linear-gradient(135deg,#10B981,#06B6D4); }

        /* Metadata */
        .meta-row { font-size:0.75rem;color:var(--mist);padding:0.5rem 1.2rem;display:flex;align-items:center;gap:0.5rem;border-bottom:1px solid rgba(255,255,255,0.04); }
        .meta-row:last-child { border-bottom:none; }
        .meta-label { width:110px;font-weight:600;flex-shrink:0; }
        .meta-val   { color:var(--silver);word-break:break-all; }

        @media(max-width:900px) { .show-layout { grid-template-columns:1fr; } .diff-panel { grid-template-columns:1fr; } .diff-side:first-child { border-right:none;border-bottom:1px solid rgba(255,255,255,0.07); } }
    </style>

    <div class="show-layout">
        {{-- LEFT --}}
        <div>

            {{-- Main info --}}
            <div class="info-card">
                <div class="info-card-head">Informasi Log</div>
                <div class="info-row">
                    <span class="info-key">ID</span>
                    <span class="info-val" style="font-family:monospace;color:var(--accent-l)">#{{ $logAktivita->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Aksi</span>
                    <span class="info-val"><span class="aksi-badge aksi-{{ $logAktivita->aksi }}">{{ $logAktivita->aksi }}</span></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Modul</span>
                    <span class="info-val"><span class="modul-chip">{{ $logAktivita->modul }}</span></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Waktu</span>
                    <span class="info-val">{{ $logAktivita->created_at->format('d M Y, H:i:s') }} ({{ $logAktivita->created_at->diffForHumans() }})</span>
                </div>
                @if($logAktivita->deskripsi)
                    <div class="info-row">
                        <span class="info-key">Deskripsi</span>
                        <span class="info-val" style="line-height:1.5">{{ $logAktivita->deskripsi }}</span>
                    </div>
                @endif
            </div>

            {{-- Data Diff --}}
            @if($logAktivita->data_lama || $logAktivita->data_baru)
                <div class="info-card">
                    <div class="info-card-head">
                        Perubahan Data
                        @if($logAktivita->data_lama && $logAktivita->data_baru)
                            @php
                                $changedKeys = collect($logAktivita->data_baru)
                                    ->filter(fn($v,$k) => ($logAktivita->data_lama[$k] ?? null) !== $v)
                                    ->keys();
                            @endphp
                            @if($changedKeys->isNotEmpty())
                                <span style="font-size:0.68rem;color:var(--mist);margin-left:0.5rem;font-weight:400">
                                    {{ $changedKeys->count() }} field berubah: {{ $changedKeys->join(', ') }}
                                </span>
                            @endif
                        @endif
                    </div>
                    <div class="diff-panel">
                        <div class="diff-side">
                            <div class="diff-label old">● Data Lama</div>
                            <div class="diff-body">
                                @if($logAktivita->data_lama)
                                    <div class="json-tree" id="jsonOld"></div>
                                @else
                                    <div class="diff-empty">Tidak ada data lama</div>
                                @endif
                            </div>
                        </div>
                        <div class="diff-side">
                            <div class="diff-label new">● Data Baru</div>
                            <div class="diff-body">
                                @if($logAktivita->data_baru)
                                    <div class="json-tree" id="jsonNew"></div>
                                @else
                                    <div class="diff-empty">Tidak ada data baru</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Raw JSON toggle --}}
                    <div style="padding:0.75rem 1rem;border-top:1px solid rgba(255,255,255,0.06)">
                        <button type="button" onclick="toggleRaw()"
                                class="btn btn-ghost btn-sm"
                                style="font-size:0.72rem">{ } Toggle Raw JSON</button>
                        <div id="rawJson" style="display:none;margin-top:0.75rem">
                            <pre style="background:rgba(0,0,0,0.3);border-radius:6px;padding:0.85rem;font-size:0.72rem;color:var(--silver);overflow-x:auto;line-height:1.6">{{ json_encode(['lama'=>$logAktivita->data_lama,'baru'=>$logAktivita->data_baru], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            @endif

            {{-- User agent --}}
            @if($logAktivita->user_agent)
                <div class="info-card">
                    <div class="info-card-head">Metadata Sesi</div>
                    <div class="meta-row"><span class="meta-label">IP Address</span><span class="meta-val" style="font-family:monospace">{{ $logAktivita->ip_address ?? '—' }}</span></div>
                    <div class="meta-row"><span class="meta-label">User Agent</span><span class="meta-val" style="font-size:0.72rem">{{ $logAktivita->user_agent }}</span></div>
                </div>
            @endif

        </div>

        {{-- RIGHT --}}
        <div>
            {{-- User card --}}
            <div class="info-card" style="margin-bottom:1rem">
                <div class="info-card-head">Pelaku Aksi</div>
                @if($logAktivita->user)
                    <div style="padding:1.1rem;display:flex;align-items:center;gap:0.9rem">
                        <div class="ua role-{{ $logAktivita->user->role }}">
                            {{ strtoupper(substr($logAktivita->user->name,0,2)) }}
                        </div>
                        <div>
                            <div style="font-size:0.9rem;font-weight:700;color:var(--cream)">{{ $logAktivita->user->name }}</div>
                            <div style="font-size:0.72rem;color:var(--mist)">{{ $logAktivita->user->email }}</div>
                            <div style="margin-top:0.3rem"><span class="badge badge-{{ $logAktivita->user->role === 'admin' ? 'red' : ($logAktivita->user->role === 'petugas' ? 'blue' : 'green') }}" style="font-size:0.62rem">{{ ucfirst($logAktivita->user->role) }}</span></div>
                        </div>
                    </div>
                    <div style="padding:0 1.1rem 1rem">
                        <a href="{{ route('admin.users.show', $logAktivita->user) }}"
                           class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;font-size:0.75rem">
                            Lihat Profil User →
                        </a>
                    </div>
                @else
                    <div style="padding:1.1rem;display:flex;align-items:center;gap:0.75rem">
                        <div style="width:38px;height:38px;border-radius:9px;background:rgba(100,116,139,0.2);display:flex;align-items:center;justify-content:center;font-size:1rem">⚙</div>
                        <div>
                            <div style="font-size:0.88rem;font-weight:700;color:var(--silver)">Sistem / Otomatis</div>
                            <div style="font-size:0.72rem;color:var(--mist)">Tidak ada user terkait</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Subject card --}}
            @if($logAktivita->subject_type)
                <div class="info-card" style="margin-bottom:1rem">
                    <div class="info-card-head">Objek Terdampak</div>
                    <div style="padding:1rem">
                        @php
                            $subjectClass = class_basename($logAktivita->subject_type);
                            $subjectRoutes = [
                                'User'       => 'admin.users.show',
                                'Alat'       => 'admin.alats.show',
                                'Kategori'   => 'admin.kategoris.show',
                                'Peminjaman' => 'admin.peminjamans.show',
                                'Pengembalian' => 'admin.pengembalians.show',
                            ];
                            $routeName = $subjectRoutes[$subjectClass] ?? null;
                        @endphp
                        @if($subject && $routeName)
                            <a href="{{ route($routeName, $subject) }}" class="subject-card">
                                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate);margin-bottom:0.4rem">{{ $subjectClass }}</div>
                                <div style="font-size:0.88rem;font-weight:700;color:var(--cream)">
                                    #{{ $logAktivita->subject_id }}
                                    @if(isset($subject->nama)) — {{ $subject->nama }}
                                    @elseif(isset($subject->name)) — {{ $subject->name }}
                                    @elseif(isset($subject->nomor_pinjam)) — {{ $subject->nomor_pinjam }}
                                    @endif
                                </div>
                                <div style="font-size:0.72rem;color:var(--accent-l);margin-top:0.4rem">Lihat detail →</div>
                            </a>
                        @else
                            <div style="padding:0.5rem 0">
                                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate);margin-bottom:0.3rem">{{ $subjectClass }}</div>
                                <div style="font-size:0.82rem;color:var(--silver)">#{{ $logAktivita->subject_id }}</div>
                                @if(!$subject)
                                    <div style="font-size:0.72rem;color:var(--danger);margin-top:0.25rem">⚠ Objek telah dihapus</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Navigation --}}
            <div class="info-card" style="padding:1rem">
                <div style="display:flex;gap:0.5rem">
                    @if($prev)
                        <a href="{{ route('admin.log-aktivitas.show', $prev) }}"
                           class="btn btn-ghost btn-sm" style="flex:1;justify-content:center">‹ Log Sebelumnya</a>
                    @endif
                    @if($next)
                        <a href="{{ route('admin.log-aktivitas.show', $next) }}"
                           class="btn btn-ghost btn-sm" style="flex:1;justify-content:center">Log Berikutnya ›</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Render JSON data with syntax highlighting + diff
        const dataLama = @json($logAktivita->data_lama ?? []);
        const dataBaru = @json($logAktivita->data_baru ?? []);

        function renderJson(data, compareData, changedOnly = false) {
            if (!data || typeof data !== 'object') return '<span style="color:var(--slate)">null</span>';

            let html = '';
            for (const [key, val] of Object.entries(data)) {
                const compareVal = compareData ? compareData[key] : undefined;
                const isChanged  = compareData !== undefined && JSON.stringify(val) !== JSON.stringify(compareVal);

                const valHtml = formatVal(val);
                const line = `<span class="json-key">"${key}"</span>: ${valHtml}`;
                html += isChanged
                    ? `<div class="field-changed-old">${line}</div>`
                    : `<div>${line}</div>`;
            }
            return html;
        }

        function formatVal(val) {
            if (val === null)           return '<span class="json-null">null</span>';
            if (typeof val === 'boolean') return `<span class="json-bool">${val}</span>`;
            if (typeof val === 'number')  return `<span class="json-num">${val}</span>`;
            if (typeof val === 'string')  return `<span class="json-str">"${val}"</span>`;
            if (typeof val === 'object')  return `{${Object.entries(val).map(([k,v]) => `<span class="json-key">"${k}"</span>: ${formatVal(v)}`).join(', ')}}`;
            return String(val);
        }

        // Render old (highlight changed fields)
        const oldEl = document.getElementById('jsonOld');
        if (oldEl && Object.keys(dataLama).length) {
            oldEl.innerHTML = renderJson(dataLama, dataBaru);
        }

        // Render new (highlight changed fields)
        const newEl = document.getElementById('jsonNew');
        if (newEl && Object.keys(dataBaru).length) {
            const html = renderJson(dataBaru, dataLama);
            // Replace old-highlight class with new-highlight class for new panel
            newEl.innerHTML = html.replace(/field-changed-old/g, 'field-changed-new');
        }

        function toggleRaw() {
            const el = document.getElementById('rawJson');
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</x-admin-layout>