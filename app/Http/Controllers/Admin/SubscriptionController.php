<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::with('user');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        $subscriptions = $query->latest()->paginate(15);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Approve a subscription (for manual payments).
     */
    public function approve(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => $subscription->plan === 'monthly' 
                ? now()->addMonth() 
                : now()->addYear(),
        ]);

        // Update user subscription
        $subscription->user->update([
            'subscription_tier' => 'premium',
            'subscription_expires_at' => $subscription->expires_at,
        ]);

        return back()->with('success', 'Subscription approved successfully!');
    }

    /**
     * Cancel a subscription.
     * For pending subscriptions, this deletes the record.
     * For active subscriptions, this marks as cancelled and reverts to free tier.
     */
    public function cancel(Subscription $subscription)
    {
        // For pending subscriptions, delete the record entirely
        if ($subscription->status === 'pending') {
            $subscription->delete();
            return back()->with('success', 'Pending subscription deleted successfully!');
        }

        // For active/other subscriptions, mark as cancelled
        $subscription->update(['status' => 'cancelled']);

        // Revert user to free tier
        $subscription->user->update([
            'subscription_tier' => 'free',
            'subscription_expires_at' => null,
        ]);

        return back()->with('success', 'Subscription cancelled successfully!');
    }
}
