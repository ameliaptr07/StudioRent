<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin|manager']);
    }

    public function index()
    {
        return response()->json(Addon::paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
        ]);

        $addon = Addon::create($data);

        return response()->json([
            'message' => 'Addon berhasil dibuat.',
            'addon'   => $addon,
        ], 201);
    }

    public function show(Addon $addon)
    {
        return response()->json($addon);
    }

    public function update(Request $request, Addon $addon)
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['sometimes', 'required', 'numeric', 'min:0'],
        ]);

        $addon->update($data);

        return response()->json([
            'message' => 'Addon berhasil diupdate.',
            'addon'   => $addon,
        ]);
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();

        return response()->json([
            'message' => 'Addon berhasil dihapus.',
        ]);
    }
}
