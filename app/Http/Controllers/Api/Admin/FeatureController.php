<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin|manager']);
    }

    public function index()
    {
        return response()->json(Feature::paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $feature = Feature::create($data);

        return response()->json([
            'message' => 'Feature berhasil dibuat.',
            'feature' => $feature,
        ], 201);
    }

    public function show(Feature $feature)
    {
        return response()->json($feature);
    }

    public function update(Request $request, Feature $feature)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
        ]);

        $feature->update($data);

        return response()->json([
            'message' => 'Feature berhasil diupdate.',
            'feature' => $feature,
        ]);
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return response()->json([
            'message' => 'Feature berhasil dihapus.',
        ]);
    }
}
