@props([
    'title',
    'description',
    'href',
    'button' => 'Ouvrir',
    'icon' => '→',
])

<a href="{{ $href }}" class="group admin-card block p-5 transition hover:-translate-y-0.5 hover:shadow-[0_10px_26px_rgba(15,23,42,0.08)]">
    <div class="flex items-start gap-4">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-lg text-slate-600 ring-1 ring-slate-200">
            {{ $icon }}
        </div>
        <div class="min-w-0">
            <h3 class="font-bold text-slate-900 group-hover:text-slate-700">{{ $title }}</h3>
            <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $description }}</p>
            <span class="mt-3 inline-flex items-center text-sm font-semibold text-slate-700">
                {{ $button }}
                <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        </div>
    </div>
</a>
