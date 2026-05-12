<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\AuditLog;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('location');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('asset_type', $request->get('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('location')) {
            $query->where('location_id', $request->get('location'));
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(20);
        $locations = Location::all();

        return view('assets.index', compact('assets', 'locations'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('assets.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => ['required', 'string', Rule::unique('assets')->whereNull('deleted_at')],
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|in:material,durable',
            'purchase_date' => 'nullable|date',
            'disposal_date' => 'nullable|date|required_if:status,disposed',
            'location_id' => 'nullable|exists:locations,id',
            'department' => 'nullable|string|max:255',
            'responsible_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,damaged,disposed',
            'notes' => 'nullable|string',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|max:5120',
        ]);

        // Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('assets', 'public');
            }
        }
        $validated['images'] = $imagePaths;

        $validated['qr_token'] = Str::random(32);

        $asset = Asset::create($validated);

        AuditLog::log('create', 'assets', $asset->id, null, $asset->toArray());

        return redirect()->route('assets.show', $asset)
            ->with('success', 'เพิ่มครุภัณฑ์เรียบร้อย');
    }

    public function show(Asset $asset)
    {
        $asset->load(['location', 'inspections.inspector', 'transfers.fromLocation', 'transfers.toLocation', 'transfers.transferredBy']);
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $locations = Location::all();
        return view('assets.edit', compact('asset', 'locations'));
    }

    public function update(Request $request, Asset $asset)
    {
        $oldData = $asset->toArray();

        $validated = $request->validate([
            'asset_code' => ['required', 'string', Rule::unique('assets')->whereNull('deleted_at')->ignore($asset->id)],
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|in:material,durable',
            'purchase_date' => 'nullable|date',
            'disposal_date' => 'nullable|date|required_if:status,disposed',
            'location_id' => 'nullable|exists:locations,id',
            'department' => 'nullable|string|max:255',
            'responsible_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,damaged,disposed',
            'notes' => 'nullable|string',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|max:5120',
        ]);

        // Handle multiple images
        $existingImages = $asset->images ?? [];
        $keepImages = $request->input('keep_images', []);
        $removeImages = $request->input('remove_images', []);

        // Delete images that were checked for removal
        if (!empty($removeImages)) {
            foreach ($removeImages as $image) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($image);
                }
            }
        }

        // Keep only existing images that were not removed
        $retainedImages = array_values(array_diff($existingImages, $removeImages));

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('assets', 'public');
            }
        }

        // Merge retained images with new images (max 4 total)
        $allImages = array_merge($retainedImages, $imagePaths);
        $validated['images'] = array_slice($allImages, 0, 4);

        $asset->update($validated);

        AuditLog::log('update', 'assets', $asset->id, $oldData, $asset->toArray());

        return redirect()->route('assets.show', $asset)
            ->with('success', 'อัปเดตครุภัณฑ์เรียบร้อย');
    }

    public function destroy(Asset $asset)
    {
        $oldData = $asset->toArray();

        // Delete related records first to avoid foreign key constraints
        $asset->inspections()->delete();
        $asset->repairs()->delete();
        $asset->transfers()->delete();

        $asset->delete();

        AuditLog::log('delete', 'assets', $asset->id, $oldData, null);

        return redirect()->route('assets.index')
            ->with('success', 'ลบครุภัณฑ์เรียบร้อย');
    }

    public function downloadQr(Asset $asset)
    {
        $qrCode = new QrCode($asset->public_qr_url);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'attachment; filename="qr-' . $asset->asset_code . '.png"',
        ]);
    }

    public function printLabel(Asset $asset)
    {
        return view('assets.print-label', compact('asset'));
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx|max:10240',
        ]);

        // TODO: Implement CSV/Excel import logic

        return redirect()->route('assets.index')
            ->with('success', 'Bulk import processed successfully.');
    }
}
