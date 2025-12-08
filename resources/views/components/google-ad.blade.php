@props(['slot' => 'content', 'class' => ''])

@php
    use App\Models\SiteSetting;

    $adsEnabled = SiteSetting::adsEnabled();
    $clientId = SiteSetting::get('ads_client_id', '');
    $adSlot = SiteSetting::get("ads_slot_{$slot}", '');

    // Only show ads if enabled and user is not premium
    $showAd = $adsEnabled && $clientId && $adSlot && (!Auth::check() || !Auth::user()->isPremium());
@endphp

@if($showAd)
<div class="google-ad {{ $class }} my-3">
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-{{ $clientId }}"
         data-ad-slot="{{ $adSlot }}"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
@endif
