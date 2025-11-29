<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureAdminController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('feature_name')->paginate(10);

        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Membuat instance baru untuk menambahkan feature
        $feature = new Feature();
        // Menyimpan data di kolom 'feature_name' sesuai dengan struktur tabel di database
        $feature->feature_name = $validated['name']; // Kolom di database adalah 'feature_name'
        $feature->description = $validated['description'] ?? null; // Menyimpan deskripsi
        $feature->save(); // Menyimpan data ke database

        return redirect()->route('admin.features.index')
            ->with('status', 'Feature berhasil ditambahkan.');
    }

    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Mengupdate data pada kolom 'feature_name'
        $feature->feature_name = $validated['name']; // Kolom di database adalah 'feature_name'
        $feature->description = $validated['description'] ?? null; // Mengupdate deskripsi
        $feature->save(); // Menyimpan perubahan data

        return redirect()->route('admin.features.index')
            ->with('status', 'Feature berhasil diperbarui.');
    }


    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('status', 'Feature berhasil dihapus.');
    }
}
