<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('assets')->get();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
            'detail' => 'nullable|string',
        ]);

        $location = Location::create($validated);

        AuditLog::log('create', 'locations', $location->id, null, $location->toArray());

        return redirect()->route('locations.index')
            ->with('success', 'Location created successfully.');
    }

    public function show(Location $location)
    {
        $location->load(['assets' => function ($q) {
            $q->latest()->limit(50);
        }]);
        return view('locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $oldData = $location->toArray();

        $validated = $request->validate([
            'building' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
            'detail' => 'nullable|string',
        ]);

        $location->update($validated);

        AuditLog::log('update', 'locations', $location->id, $oldData, $location->toArray());

        return redirect()->route('locations.index')
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $oldData = $location->toArray();
        $location->delete();

        AuditLog::log('delete', 'locations', $location->id, $oldData, null);

        return redirect()->route('locations.index')
            ->with('success', 'Location deleted successfully.');
    }
}
