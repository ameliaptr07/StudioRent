<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    /**
     * Menampilkan daftar studio (versi admin).
     */
    public function index()
    {
        $studios = Studio::with(['addons', 'features'])->get();

        return response()->json([
            'data' => $studios,
        ]);
    }

    /**
     * Menyimpan studio baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'capacity'            => ['required', 'integer', 'min:1'],
            'price_per_hour'      => ['required', 'numeric', 'min:0'],
            'location'            => ['nullable', 'string', 'max:255'],
            'assigned_manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'status'              => ['nullable', 'in:active,inactive'],
            'addon_ids'           => ['sometimes', 'array'],
            'addon_ids.*'         => ['integer', 'exists:addons,id'],
            'feature_ids'         => ['sometimes', 'array'],
            'feature_ids.*'       => ['integer', 'exists:features,id'],
        ]);

        $studio = Studio::create([
            'name'                => $data['name'],
            'description'         => $data['description'] ?? null,
            'capacity'            => $data['capacity'],
            'price_per_hour'      => $data['price_per_hour'],
            'location'            => $data['location'] ?? null,
            'assigned_manager_id' => $data['assigned_manager_id'] ?? null,
            'status'              => $data['status'] ?? 'active',
        ]);

        if (!empty($data['addon_ids'] ?? [])) {
            $studio->addons()->sync($data['addon_ids']);
        }

        if (!empty($data['feature_ids'] ?? [])) {
            $studio->features()->sync($data['feature_ids']);
        }

        $studio->load(['addons', 'features']);

        return response()->json([
            'message' => 'Studio berhasil dibuat.',
            'studio'  => $studio,
        ], 201);
    }

    /**
     * Menampilkan detail studio.
     */
    public function show(Studio $studio)
    {
        $studio->load(['addons', 'features']);

        return response()->json([
            'studio' => $studio,
        ]);
    }

    /**
     * Mengupdate data studio.
     */
    public function update(Request $request, Studio $studio)
    {
        $data = $request->validate([
            'name'                => ['sometimes', 'required', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'capacity'            => ['sometimes', 'required', 'integer', 'min:1'],
            'price_per_hour'      => ['sometimes', 'required', 'numeric', 'min:0'],
            'location'            => ['nullable', 'string', 'max:255'],
            'assigned_manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'status'              => ['nullable', 'in:active,inactive'],
            'addon_ids'           => ['sometimes', 'array'],
            'addon_ids.*'         => ['integer', 'exists:addons,id'],
            'feature_ids'         => ['sometimes', 'array'],
            'feature_ids.*'       => ['integer', 'exists:features,id'],
        ]);

        $studio->fill($data);
        $studio->save();

        if (array_key_exists('addon_ids', $data)) {
            $studio->addons()->sync($data['addon_ids'] ?? []);
        }

        if (array_key_exists('feature_ids', $data)) {
            $studio->features()->sync($data['feature_ids'] ?? []);
        }

        $studio->load(['addons', 'features']);

        return response()->json([
            'message' => 'Studio berhasil diupdate.',
            'studio'  => $studio,
        ]);
    }

    /**
     * Menghapus studio.
     */
    public function destroy(Studio $studio)
    {
        $studio->delete();

        return response()->json([
            'message' => 'Studio berhasil dihapus.',
        ]);
    }
}
