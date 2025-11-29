<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{

    /*
    |-------------------------------------------------------------------------- 
    | Dashboard
    |-------------------------------------------------------------------------- 
    */
    public function dashboard()
    {
        return view('manager.dashboard');  // Sesuaikan dengan view yang telah kamu buat
    }

    /*
    |-------------------------------------------------------------------------- 
    | Profile
    |-------------------------------------------------------------------------- 
    */
    public function profile()
    {
        return view('manager.profile');
    }

public function updateProfile(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        'password' => 'nullable|string|min:8|confirmed', // Password boleh kosong, jika tidak diubah
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk gambar
    ]);

    // Ambil data user yang sedang login
    $user = auth()->user();

    // Update nama dan email
    $user->name = $request->name;
    $user->email = $request->email;

    // Jika password diubah, maka update passwordnya
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Jika ada gambar yang diupload, proses gambar tersebut
    if ($request->hasFile('profile_picture')) {
        // Hapus gambar lama jika ada
        if ($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture))) {
            unlink(storage_path('app/public/' . $user->profile_picture));
        }

        // Simpan gambar baru
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
    }

    // Simpan perubahan data user
    $user->save();

    // Redirect kembali ke dashboard manager setelah sukses
    return redirect()->route('manager.dashboard')->with('success', 'Profil berhasil diperbarui!');
}

    /*
    |-------------------------------------------------------------------------- 
    | Reports
    |-------------------------------------------------------------------------- 
    */
    public function reports(Request $request)
    {
        // Query dasar untuk mengambil data reservasi
        $query = Reservation::with(['studio', 'user']);
        
        // Jika ada parameter pencarian
        if ($request->has('search') && $request->search) {
            $query->whereHas('studio', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil data yang sudah difilter
        $reservations = $query->get();

        // Kirim data ke tampilan
        return view('manager.reports.reservations', compact('reservations'));
    }

    /*
    |-------------------------------------------------------------------------- 
    | Team
    |-------------------------------------------------------------------------- 
    */
    // Team Method untuk Menampilkan Admin
    public function team()
    {
        // Ambil data user yang memiliki role 'Admin' atau 'Super Admin'
        $adminUsers = User::whereHas('role', function ($query) {
            $query->where('name', 'Admin') // atau 'Super Admin' tergantung nama role yang digunakan
                  ->orWhere('name', 'Super Admin');
        })->get(); // Mengambil semua admin yang memiliki role 'Admin' atau 'Super Admin'

        return view('manager.team', compact('adminUsers'));
    }

    // Toggle Status Method untuk Update Status Admin
    public function toggleStatus($id)
    {
        // Ambil data admin berdasarkan ID
        $admin = User::findOrFail($id);

        // Toggle status admin (aktif <=> tidak aktif)
        $admin->is_active = !$admin->is_active;
        $admin->save(); // Simpan perubahan ke database

        // Redirect kembali ke halaman daftar admin dengan pesan sukses
        return redirect()->route('manager.team')->with('success', 'Status admin berhasil diperbarui!');
    }

    // Delete Method untuk Menghapus Admin
    public function delete($id)
    {
        $admin = User::findOrFail($id); // Ambil admin berdasarkan ID
        $admin->delete(); // Hapus admin dari database

        return redirect()->route('manager.team')->with('success', 'Admin berhasil dihapus!');
    }

    // Store Method untuk Menambahkan Admin Baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Pastikan password dihash
            'is_active' => 'required|boolean',
        ]);

        // Simpan admin baru ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash
            'role_id' => 1, // Pastikan role_id untuk admin
            'is_active' => $request->is_active,
        ]);

        // Redirect ke halaman team dengan pesan sukses
        return redirect()->route('manager.team')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    // Edit Method untuk Menampilkan Form Edit Admin
    public function edit($id)
    {
        $admin = User::findOrFail($id); // Ambil data admin berdasarkan ID
        return view('manager.team.edit', compact('admin')); // Kirim data admin ke view edit
    }

    // Update Method untuk Memperbarui Data Admin
    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed', // password boleh kosong, jika diisi baru dihash
            'is_active' => 'required|boolean',
        ]);

        $admin = User::findOrFail($id); // Cari admin berdasarkan ID
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->is_active = $request->is_active; // Update status aktif/tidak aktif

        // Jika password diisi, maka diupdate
        if ($request->password) {
            $admin->password = Hash::make($request->password); // Password di-hash
        }

        $admin->save(); // Simpan perubahan

        // Redirect ke halaman team dengan pesan sukses
        return redirect()->route('manager.team')->with('success', 'Admin berhasil diperbarui!');
    }

    public function create()
    {
        // Menampilkan form untuk menambahkan admin baru
        return view('manager.team.create');
    }

}
