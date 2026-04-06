<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX  —  List all users with filter & search
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = User::query()->latest();

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->withCount([
            'peminjamans',
            'peminjamans as peminjaman_aktif_count' => fn($q) =>
                $q->whereIn('status', ['disetujui', 'dipinjam']),
        ])->paginate(12)->withQueryString();

        $stats = [
            'total'    => User::count(),
            'admin'    => User::where('role', User::ROLE_ADMIN)->count(),
            'petugas'  => User::where('role', User::ROLE_PETUGAS)->count(),
            'peminjam' => User::where('role', User::ROLE_PEMINJAM)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE  —  Show form
    // ─────────────────────────────────────────────
    public function create(): View
    {
        return view('admin.users.create');
    }

    // ─────────────────────────────────────────────
    //  STORE  —  Save new user
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users'],
            'no_hp'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'role'                  => ['required', Rule::in([
                                            User::ROLE_ADMIN,
                                            User::ROLE_PETUGAS,
                                            User::ROLE_PEMINJAM,
                                        ])],
        ], [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_USER,
            modul: 'User',
            deskripsi: "User baru ditambahkan: {$user->name} ({$user->role})",
            subject: $user,
            dataBaru: $user->only('name', 'email', 'role'),
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil ditambahkan.");
    }

    // ─────────────────────────────────────────────
    //  SHOW  —  User detail + activity log
    // ─────────────────────────────────────────────
    public function show(User $user): View
    {
        $user->loadCount([
            'peminjamans',
            'peminjamans as peminjaman_aktif_count' => fn($q) =>
                $q->whereIn('status', ['disetujui', 'dipinjam']),
            'peminjamans as peminjaman_selesai_count' => fn($q) =>
                $q->where('status', 'dikembalikan'),
        ]);

        $riwayat = $user->peminjamans()
            ->with('alat:id,nama,kode')
            ->latest()
            ->take(10)
            ->get();

        $logAktivitas = $user->logAktivitas()
            ->latest()
            ->take(15)
            ->get();

        return view('admin.users.show', compact('user', 'riwayat', 'logAktivitas'));
    }

    // ─────────────────────────────────────────────
    //  EDIT  —  Show edit form
    // ─────────────────────────────────────────────
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE  —  Save changes
    // ─────────────────────────────────────────────
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_hp'    => ['nullable', 'string', 'max:20'],
            'role'     => ['required', Rule::in([
                                User::ROLE_ADMIN,
                                User::ROLE_PETUGAS,
                                User::ROLE_PEMINJAM,
                           ])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan oleh user lain.',
            'role.required'      => 'Role wajib dipilih.',
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $before = $user->only('name', 'email', 'role');

        $user->fill([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
            'no_hp' => $validated['no_hp'],
        ]);

        // Only update password if provided
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_USER,
            modul: 'User',
            deskripsi: "Data user diperbarui: {$user->name}",
            subject: $user,
            dataLama: $before,
            dataBaru: $user->only('name', 'email', 'role'),
        );

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Data user {$user->name} berhasil diperbarui.");
    }

    // ─────────────────────────────────────────────
    //  DESTROY  —  Delete user
    // ─────────────────────────────────────────────
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting user with active loans
        $aktif = $user->peminjamans()->whereIn('status', ['disetujui', 'dipinjam'])->count();
        if ($aktif > 0) {
            return back()->with('error', "User {$user->name} masih memiliki {$aktif} peminjaman aktif.");
        }

        $name = $user->name;

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_USER,
            modul: 'User',
            deskripsi: "User dihapus: {$name} ({$user->role})",
            dataLama: $user->only('name', 'email', 'role'),
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}