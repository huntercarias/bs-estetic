<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomFieldDefinition;
use App\Models\Service;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        $fields = CustomFieldDefinition::with('service')->orderBy('service_id')->orderBy('order')->paginate(30);

        return view('admin.custom-fields.index', compact('fields'));
    }

    public function create()
    {
        $services = Service::where('active', true)->get();

        return view('admin.custom-fields.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'label'      => 'required|string|max:255',
            'name'       => 'required|string|max:100|alpha_dash',
            'type'       => 'required|in:text,number,date,select,boolean,textarea',
            'options'    => 'nullable|string',
            'required'   => 'boolean',
            'order'      => 'integer',
        ]);

        if ($validated['type'] === 'select' && !empty($validated['options'])) {
            $optionsList = array_filter(array_map('trim', explode("\n", $validated['options'])));
            $validated['options'] = $optionsList;
        } else {
            $validated['options'] = null;
        }

        $validated['required'] = $request->boolean('required');

        CustomFieldDefinition::create($validated);

        return redirect()->route('admin.custom-fields.index')
            ->with('success', 'Campo personalizado creado.');
    }

    public function edit(CustomFieldDefinition $customField)
    {
        $services = Service::where('active', true)->get();

        return view('admin.custom-fields.edit', compact('customField', 'services'));
    }

    public function update(Request $request, CustomFieldDefinition $customField)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'label'      => 'required|string|max:255',
            'type'       => 'required|in:text,number,date,select,boolean,textarea',
            'options'    => 'nullable|string',
            'required'   => 'boolean',
            'order'      => 'integer',
        ]);

        if ($validated['type'] === 'select' && !empty($validated['options'])) {
            $optionsList = array_filter(array_map('trim', explode("\n", $validated['options'])));
            $validated['options'] = $optionsList;
        } else {
            $validated['options'] = null;
        }

        $validated['required'] = $request->boolean('required');
        $customField->update($validated);

        return redirect()->route('admin.custom-fields.index')
            ->with('success', 'Campo actualizado.');
    }

    public function destroy(CustomFieldDefinition $customField)
    {
        $customField->delete();

        return redirect()->route('admin.custom-fields.index')
            ->with('success', 'Campo eliminado.');
    }
}
