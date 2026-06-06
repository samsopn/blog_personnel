@extends('layouts.application')

@section('title', 'Notifications — ' . config('app.name'))

@section('contenu')
    <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Notifications</h1>
            </div>

            @if ($notifications->contains(fn ($notification) => ! $notification->estLue()))
                <form method="POST" action="{{ route('user.notifications.tout-lu') }}">
                    @csrf
                    <button type="submit" class="admin-btn-secondary !py-2 !text-xs">
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        @if ($notifications->isEmpty())
            <p class="rounded-lg bg-slate-50 px-4 py-6 text-center text-sm text-slate-600">
                Aucune notification pour le moment.
            </p>
        @else
            <ul class="divide-y divide-slate-200">
                @foreach ($notifications as $notification)
                    <li @class(['py-4', 'bg-brand-50/40 -mx-2 px-2 rounded-lg' => ! $notification->estLue()])>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-sm text-slate-800">
                                    <span class="font-semibold text-slate-900">{{ $notification->mentionnePar->name }}</span>
                                    vous a mentionné sur
                                    <span class="font-semibold text-brand-700">{{ $notification->article->title }}</span>
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $notification->created_at->locale('fr')->diffForHumans() }}
                                    @if (! $notification->estLue())
                                        · <span class="font-semibold text-brand-600">Non lu</span>
                                    @endif
                                </p>
                            </div>

                            <form method="POST" action="{{ route('user.notifications.lu', $notification) }}" class="shrink-0">
                                @csrf
                                <button type="submit" class="admin-btn-primary !py-1.5 !text-xs">
                                    Voir le commentaire
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
