<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of subscription packages.
     */
    public function index()
    {
        $packages = SubscriptionPackage::ordered()->get();

        return view('admin.settings.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('admin.settings.packages.create');
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');
        $validated['features'] = array_filter($request->input('features', []));

        SubscriptionPackage::create($validated);

        return redirect()->route('admin.settings.packages.index')
            ->with('success', 'Subscription package created successfully!');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(SubscriptionPackage $package)
    {
        return view('admin.settings.packages.edit', compact('package'));
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, SubscriptionPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');
        $validated['features'] = array_filter($request->input('features', []));

        $package->update($validated);

        return redirect()->route('admin.settings.packages.index')
            ->with('success', 'Subscription package updated successfully!');
    }

    /**
     * Remove the specified package.
     */
    public function destroy(SubscriptionPackage $package)
    {
        // Check if there are active subscriptions using this package
        if ($package->subscriptions()->exists()) {
            return back()->with('error', 'Cannot delete package with active subscriptions.');
        }

        $package->delete();

        return redirect()->route('admin.settings.packages.index')
            ->with('success', 'Subscription package deleted successfully!');
    }
}
