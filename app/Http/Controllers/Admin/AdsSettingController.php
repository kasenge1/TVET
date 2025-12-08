<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdsSettingController extends Controller
{
    /**
     * Display the ads settings form.
     */
    public function index()
    {
        $adsSettings = SiteSetting::getAdsSettings();

        return view('admin.settings.ads.index', compact('adsSettings'));
    }

    /**
     * Update the ads settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'ads_enabled' => 'boolean',
            'ads_client_id' => 'nullable|string|max:255',
            'ads_slot_header' => 'nullable|string|max:255',
            'ads_slot_sidebar' => 'nullable|string|max:255',
            'ads_slot_content' => 'nullable|string|max:255',
        ]);

        // Save each setting
        SiteSetting::set('ads_enabled', $request->has('ads_enabled') ? '1' : '0', 'ads');
        SiteSetting::set('ads_client_id', $request->input('ads_client_id', ''), 'ads');
        SiteSetting::set('ads_slot_header', $request->input('ads_slot_header', ''), 'ads');
        SiteSetting::set('ads_slot_sidebar', $request->input('ads_slot_sidebar', ''), 'ads');
        SiteSetting::set('ads_slot_content', $request->input('ads_slot_content', ''), 'ads');

        return back()->with('success', 'Google Ads settings updated successfully!');
    }
}
