@props(['active' => false, 'compact' => false])

<button
    type="submit"
    data-interaction-button
    aria-label="{{ $active ? 'Retirer le like' : 'Ajouter un like' }}"
    @class([
        'inline-flex items-center justify-center rounded transition',
        'rounded-full border p-2.5' => ! $compact,
        'p-0.5' => $compact,
        'border-brand-200 bg-brand-50 text-brand-600 hover:bg-brand-100' => $active && ! $compact,
        'border-slate-200 bg-white text-slate-500 hover:border-brand-200 hover:text-brand-600' => ! $active && ! $compact,
        'text-brand-600' => $active && $compact,
        'text-slate-400 hover:text-brand-600' => ! $active && $compact,
    ])
>
    <svg
        @class(['h-5 w-5' => ! $compact, 'h-3.5 w-3.5' => $compact])
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        aria-hidden="true"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H9.494a2.25 2.25 0 01-2.244-2.077 4.502 4.502 0 00-1.423-.23H6.633z" />
    </svg>
</button>
