<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogAktivitasController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = LogAktivitas::with('user:id,name,role')->latest('created_at');

        // Search deskripsi
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('aksi',    'like', "%{$search}%")
                  ->orWhere('modul',   'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($aksi = $request->input('aksi')) {
            $query->where('aksi', $aksi);
        }

        if ($modul = $request->input('modul')) {
            $query->where('modul', $modul);
        }

        if ($userId = $request->input('user')) {
            $query->where('user_id', $userId);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        if ($request->input('today')) {
            $query->today();
        }

        $logs = $query->paginate(25)->withQueryString();

        // Stats
        $stats = [
            'total'   => LogAktivitas::count(),
            'today'   => LogAktivitas::today()->count(),
            'users'   => LogAktivitas::whereNotNull('user_id')->distinct('user_id')->count(),
            'moduls'  => LogAktivitas::distinct('modul')->count(),
        ];

        // For filter dropdowns
        $allAksi  = LogAktivitas::distinct()->orderBy('aksi')->pluck('aksi');
        $allModul = LogAktivitas::distinct()->orderBy('modul')->pluck('modul');
        $allUsers = User::whereIn('id', LogAktivitas::whereNotNull('user_id')->distinct()->pluck('user_id'))
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return view('admin.log-aktivitas.index', compact('logs', 'stats', 'allAksi', 'allModul', 'allUsers'));
    }

    // ─────────────────────────────────────────────
    //  SHOW — detail single log entry
    // ─────────────────────────────────────────────
    public function show(LogAktivitas $logAktivita): View
    {
        $logAktivita->load('user');

        // Try to load the subject model (polymorphic)
        $subject = null;
        if ($logAktivita->subject_type && $logAktivita->subject_id) {
            try {
                $subject = ($logAktivita->subject_type)::withTrashed()
                    ->find($logAktivita->subject_id);
            } catch (\Throwable) {
                $subject = null;
            }
        }

        // Neighbour logs (prev/next by time)
        $prev = LogAktivitas::where('id', '<', $logAktivita->id)->latest('id')->first(['id']);
        $next = LogAktivitas::where('id', '>', $logAktivita->id)->oldest('id')->first(['id']);

        return view('admin.log-aktivitas.show', compact('logAktivita', 'subject', 'prev', 'next'));
    }

    // ─────────────────────────────────────────────
    //  CREATE  — manual log entry (for admins)
    // ─────────────────────────────────────────────
    public function create(): View
    {
        $aksiOptions = [
            LogAktivitas::AKSI_LOGIN               => 'Login',
            LogAktivitas::AKSI_LOGOUT              => 'Logout',
            LogAktivitas::AKSI_CRUD_USER           => 'CRUD User',
            LogAktivitas::AKSI_CRUD_ALAT           => 'CRUD Alat',
            LogAktivitas::AKSI_CRUD_KATEGORI       => 'CRUD Kategori',
            LogAktivitas::AKSI_CRUD_PEMINJAMAN     => 'CRUD Peminjaman',
            LogAktivitas::AKSI_CRUD_PENGEMBALIAN   => 'CRUD Pengembalian',
            LogAktivitas::AKSI_SETUJUI_PEMINJAMAN  => 'Setujui Peminjaman',
            LogAktivitas::AKSI_TOLAK_PEMINJAMAN    => 'Tolak Peminjaman',
            LogAktivitas::AKSI_PANTAU_PENGEMBALIAN => 'Pantau Pengembalian',
            LogAktivitas::AKSI_CETAK_LAPORAN       => 'Cetak Laporan',
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('admin.log-aktivitas.create', compact('aksiOptions', 'users'));
    }

    // ─────────────────────────────────────────────
    //  STORE  — manual log entry
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'aksi'       => ['required', 'string', 'max:100'],
            'modul'      => ['required', 'string', 'max:100'],
            'deskripsi'  => ['nullable', 'string', 'max:1000'],
            'user_id'    => ['nullable', 'exists:users,id'],
            'data_baru'  => ['nullable', 'json'],
        ], [
            'aksi.required'  => 'Aksi wajib diisi.',
            'modul.required' => 'Modul wajib diisi.',
            'data_baru.json' => 'Format data harus JSON valid.',
        ]);

        $log = LogAktivitas::create([
            'user_id'    => $validated['user_id'] ?? auth()->id(),
            'aksi'       => $validated['aksi'],
            'modul'      => $validated['modul'],
            'deskripsi'  => $validated['deskripsi'],
            'data_baru'  => $validated['data_baru'] ? json_decode($validated['data_baru'], true) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.log-aktivitas.show', $log)
            ->with('success', 'Log aktivitas berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────
    //  EDIT — log is immutable; redirect back
    // ─────────────────────────────────────────────
    public function edit(LogAktivitas $logAktivita): RedirectResponse
    {
        // Log entries must not be modified — redirect to show
        return redirect()
            ->route('admin.log-aktivitas.show', $logAktivita)
            ->with('info', 'Log aktivitas bersifat immutable dan tidak dapat diedit.');
    }

    // ─────────────────────────────────────────────
    //  PURGE  — admin can purge old logs
    // ─────────────────────────────────────────────
    public function purge(Request $request): RedirectResponse
    {
        $request->validate([
            'days' => ['required', 'integer', 'min:7'],
        ]);

        $cutoff = now()->subDays($request->input('days'));
        $count  = LogAktivitas::where('created_at', '<', $cutoff)->count();
        LogAktivitas::where('created_at', '<', $cutoff)->delete();

        // Log the purge itself
        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_USER,
            modul: 'Log',
            deskripsi: "Purge log: {$count} entri dihapus (lebih dari {$request->input('days')} hari)",
        );

        return back()->with('success', "{$count} entri log berhasil dihapus.");
    }
}