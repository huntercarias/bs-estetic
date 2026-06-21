@extends('layouts.admin')
@section('title','Pacientes')
@section('header','Gestión de Pacientes')

@section('content')
<div class="flex justify-between items-center mb-5">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o email..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-indigo-500">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Buscar</button>
    </form>
    <a href="{{ route('admin.patients.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
        <i class="fa-solid fa-user-plus mr-1"></i> Nuevo Paciente
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">Paciente</th>
                <th class="px-5 py-3 text-left">Edad</th>
                <th class="px-5 py-3 text-left">Objetivo</th>
                <th class="px-5 py-3 text-left">Entrena en</th>
                <th class="px-5 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($patients as $patient)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-800">{{ $patient->name }}</div>
                    <div class="text-xs text-gray-400">{{ $patient->email }}</div>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $patient->profile?->age ? $patient->profile->age.' años' : '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $patient->profile?->goal_label ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $patient->profile?->trains_at_label ?? '—' }}</td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('admin.patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-800 mr-2">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.patients.edit', $patient) }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No hay pacientes registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $patients->links() }}</div>
@endsection
