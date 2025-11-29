<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonAdminController extends Controller
{
    public function index()
    {
        $addons = Addon::orderBy('name')->paginate(10);

        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $addon = new Addon();
        $addon->name = $validated['name'];
        $addon->description = $validated['description'] ?? null;
        $addon->price = $validated['price'];
        $addon->save();

        return redirect()->route('admin.addons.index')
            ->with('status', 'Addon berhasil ditambahkan.');
    }

    public function edit(Addon $addon)
    {
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $addon->name = $validated['name'];
        $addon->description = $validated['description'] ?? null;
        $addon->price = $validated['price'];
        $addon->save();

        return redirect()->route('admin.addons.index')
            ->with('status', 'Addon berhasil diperbarui.');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();

        return redirect()->route('admin.addons.index')
            ->with('status', 'Addon berhasil dihapus.');
    }
}
