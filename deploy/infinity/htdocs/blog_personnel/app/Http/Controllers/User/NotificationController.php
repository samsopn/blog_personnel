<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::query()
            ->where('user_id', auth()->id())
            ->with(['mentionnePar', 'article', 'commentaire'])
            ->latest()
            ->paginate(15);

        return view('user.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function marquerCommeLue(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->marquerCommeLue();

        return redirect($notification->url());
    }

    public function toutMarquerCommeLu(): RedirectResponse
    {
        Notification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()
            ->route('user.notifications')
            ->with('succes', 'Toutes les notifications ont été marquées comme lues.');
    }
}
