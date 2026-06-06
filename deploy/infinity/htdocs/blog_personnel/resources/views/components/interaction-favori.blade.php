@props(['active' => false])

<button
    type="submit"
    data-interaction-button
    aria-label="{{ $active ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
    @class([
        'inline-flex items-center justify-center rounded-full border p-2.5 transition',
        'border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-100' => $active,
        'border-slate-200 bg-white text-slate-500 hover:border-amber-200 hover:text-amber-500' => ! $active,
    ])
>
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885-4.725 2.885a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
    </svg>
</button>
