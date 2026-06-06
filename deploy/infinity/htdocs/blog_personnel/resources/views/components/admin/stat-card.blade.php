@props([
    'label',
    'value',
    'hint' => null,
    'icon' => '•',
    'tone' => 'indigo',
])

<div class="admin-card p-5">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-sm text-slate-500">{{ $hint }}</p>
            @endif
        </div>
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 ring-1 ring-slate-200 text-lg">
            {{ $icon }}
        </div>
    </div>
</div>
