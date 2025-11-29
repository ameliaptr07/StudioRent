<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Addon;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudioAdminController extends Controller
{
    public function index()
    {
        $studios = Studio::orderBy('name')->paginate(20);
        return view('admin.studios.index', compact('studios'));
    }

    public function create()
    {
        $addons = Addon::all();
        $features = Feature::all();
        return view('admin.studios.create', compact('addons', 'features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'capacity'       => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'status'         => 'required|in:active,inactive',
            'addons'         => 'nullable|array',
            'addons.*'       => 'exists:addons,id',
            'features'       => 'nullable|array',
            'features.*'     => 'exists:features,id',

            // ✅ image upload
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg', 'max:2048'],
        ]);

        $studio = new Studio();
        $studio->name = $validated['name'];
        $studio->description = $validated['description'] ?? null;
        $studio->capacity = $validated['capacity'];
        $studio->price_per_hour = $validated['price_per_hour'];
        $studio->status = $validated['status'];
        $studio->save();

        // ✅ sync addons/features (biar kalau kosong = detach)
        $studio->addons()->sync($validated['addons'] ?? []);
        $studio->features()->sync($validated['features'] ?? []);

        // ✅ simpan image setelah ada id
        if ($request->hasFile('image')) {
            $slug = Str::slug($studio->name);
            $ext = strtolower($request->file('image')->getClientOriginalExtension() ?: 'jpg');
            $filename = "studio-{$studio->id}-{$slug}.{$ext}";

            $path = $request->file('image')->storeAs('studios', $filename, 'public');
            $studio->image_path = $path; // contoh: studios/studio-12-studio-musik-a.jpg
            $studio->save();
        }

        return redirect()->route('admin.studios.index')
            ->with('status', 'Studio berhasil ditambahkan.');
    }

    public function edit(Studio $studio)
    {
        $addons = Addon::all();
        $features = Feature::all();
        return view('admin.studios.edit', compact('studio', 'addons', 'features'));
    }

    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'capacity'       => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'status'         => 'required|in:active,inactive',
            'addons'         => 'nullable|array',
            'addons.*'       => 'exists:addons,id',
            'features'       => 'nullable|array',
            'features.*'     => 'exists:features,id',

            // ✅ image upload
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg', 'max:2048'],
        ]);

        $studio->name = $validated['name'];
        $studio->description = $validated['description'] ?? null;
        $studio->capacity = $validated['capacity'];
        $studio->price_per_hour = $validated['price_per_hour'];
        $studio->status = $validated['status'];
        $studio->save();

        $studio->addons()->sync($validated['addons'] ?? []);
        $studio->features()->sync($validated['features'] ?? []);

        // ✅ jika upload image baru: hapus yang lama (khusus yang tersimpan di disk public/studios)
        if ($request->hasFile('image')) {
            if ($studio->image_path && str_starts_with($studio->image_path, 'studios/')) {
                if (Storage::disk('public')->exists($studio->image_path)) {
                    Storage::disk('public')->delete($studio->image_path);
                }
            }

            $slug = Str::slug($studio->name);
            $ext = strtolower($request->file('image')->getClientOriginalExtension() ?: 'jpg');
            $filename = "studio-{$studio->id}-{$slug}.{$ext}";

            $path = $request->file('image')->storeAs('studios', $filename, 'public');
            $studio->image_path = $path;
            $studio->save();
        }

        return redirect()->route('admin.studios.index')
            ->with('status', 'Studio berhasil diperbarui.');
    }

    public function destroy(Studio $studio)
    {
        // ✅ bersihkan file gambar (kalau dari storage/public)
        if ($studio->image_path && str_starts_with($studio->image_path, 'studios/')) {
            if (Storage::disk('public')->exists($studio->image_path)) {
                Storage::disk('public')->delete($studio->image_path);
            }
        }

        $studio->delete();

        return redirect()->route('admin.studios.index')
            ->with('status', 'Studio berhasil dihapus.');
    }

    public function calendar(Studio $studio)
    {
        $studioAvailability = $studio->reservations->map(function ($reservation) {
            return [
                'title' => 'Reserved',
                'start' => $reservation->start_time,
                'end'   => $reservation->end_time,
            ];
        });

        return view('admin.studios.calendar', compact('studioAvailability'));
    }

    public function toggleStatus(\App\Models\Studio $studio)
    {
        $studio->status = strtolower($studio->status) === 'active' ? 'inactive' : 'active';
        $studio->save();

        return back()->with('status', 'Status studio berhasil diubah menjadi ' . strtoupper($studio->status) . '.');
    }
}
