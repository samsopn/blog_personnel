@props(['active' => false])

<button
    type="submit"
    data-interaction-button
    aria-label="{{ $active ? 'Retirer le like' : 'Ajouter un like' }}"
    @class([
        'inline-flex items-center justify-center rounded-full border p-2.5 transition',
        'border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100' => $active,
        'border-slate-200 bg-white text-slate-500 hover:border-rose-200 hover:text-rose-500' => ! $active,
    ])
>
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
    </svg>
</button>
