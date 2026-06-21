<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    public function edit()
    {
        $tenant = auth()->user()->tenant;
        return view('admin.settings.edit', compact('tenant'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'phone'       => 'nullable|string|max:30',
            'email'       => 'nullable|email|max:255',
            'address'     => 'nullable|string|max:255',
            'logo'        => 'nullable|image|max:2048',
        ]);

        $tenant = auth()->user()->tenant;

        $data = $request->only(['name', 'description', 'phone', 'email', 'address']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $tenant->update($data);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Información actualizada correctamente.');
    }
}
