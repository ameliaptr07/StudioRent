<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::all();

        return response()->json([
            'data' => $features,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'feature_name' => ['required', 'string', 'max:255'],
        ]);

        $feature = Feature::create($data);

        return response()->json([
            'message' => 'Feature berhasil dibuat.',
            'feature' => $feature,
        ], 201);
    }

    public function show(Feature $feature)
    {
        return response()->json([
            'feature' => $feature,
        ]);
    }

    public function update(Request $request, Feature $feature)
    {
        $data = $request->validate([
            'feature_name' => ['sometimes', 'required', 'string', 'max:255'],
        ]);

        $feature->fill($data);
        $feature->save();

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
