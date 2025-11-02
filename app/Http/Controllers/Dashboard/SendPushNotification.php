<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\User;
use App\Services\FirebasePushNotifications;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;

class SendPushNotification extends Controller
{
    public function notifyUsers()
    {
        return view('admin.notifications.notify-users');
    }

    public function notifyCashiers()
    {
        return view('admin.notifications.notify-cashiers');
    }

    public function sendUsersNotification(Request $request, FirebasePushNotifications $notification)
    {
        $request->validate([
            'title'                 => 'required|string',
            'description'           => 'required|string',
        ]);

        $users_tokens = User::whereDoesntHave('roles')
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')->toArray();

        foreach ($users_tokens as $token) {
            dispatch(new SendFirebaseNotificationJob($request->title , $request->description , $token));
        }

        return redirect()->route('dashboard')
            ->with('success', __('Notifications Sent Successfully'));
    }

    public function sendCashierNotification(Request $request, FirebasePushNotifications $notification)
    {
        $request->validate([
            'title'                 => 'required|string',
            'description'           => 'required|string',
            'service_provider_ids'  => 'nullable|array',
            'service_provider_ids.*' => 'required|exists:service_providers,id',
        ]);

        $users_tokens = User::role('cashier')
            ->whereNotNull('fcm_token')
            ->when($request->filled('service_provider_ids'), function ($query) use ($request) {
                $query->whereHas('cashierOf', function ($q) use ($request) {
                    $q->whereIn('id', $request->service_provider_ids);
                });
            })
            ->pluck('fcm_token')
            ->toArray();


        foreach ($users_tokens as $token) {
            dispatch(new SendFirebaseNotificationJob($request->title , $request->description , $token));
        }

        return redirect()->route('dashboard')
            ->with('success', __('Notifications Sent Successfully'));
    }
}
