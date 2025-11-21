<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function __construct()
    {
        // Admin & manager untuk operasi CRUD penuh,
        // tapi index & show boleh diakses tanpa role khusus (sudah di-route).
        $this->middleware(['auth', 'role:admin|manager'])->except(['index', 'show']);
    }

    /**
     * List studio (bisa difilter kapasitas & status).
     */
    public function index(Request $request)
    {
        $query = Studio::query();

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(10));
    }

    public function show(Studio $studio)
    {
        return response()->json($studio);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'capacity'       => ['required', 'integer', 'min:1'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'status'         => ['nullable'], // biarkan sesuai tipe di DB
        ]);

        $studio = Studio::create($data);

        return response()->json([
            'message' => 'Studio berhasil dibuat.',
            'studio'  => $studio,
        ], 201);
    }

    public function update(Request $request, Studio $studio)
    {
        $data = $request->validate([
            'name'           => ['sometimes', 'required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'capacity'       => ['sometimes', 'required', 'integer', 'min:1'],
            'price_per_hour' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status'         => ['nullable'],
        ]);

        $studio->update($data);

        return response()->json([
            'message' => 'Studio berhasil diupdate.',
            'studio'  => $studio,
        ]);
    }

    public function destroy(Studio $studio)
    {
        $studio->delete();

        return response()->json([
            'message' => 'Studio berhasil dihapus.',
        ]);
    }
}
