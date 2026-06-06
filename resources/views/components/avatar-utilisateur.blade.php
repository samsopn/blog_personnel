@props(['utilisateur'])

@php
    $url = $utilisateur->urlAvatar();
@endphp

@if ($url)
    <img
        src="{{ $url }}"
        alt=""
        class="h-5 w-5 shrink-0 rounded-full object-cover bg-slate-100"
        width="20"
        height="20"
        loading="lazy"
    >
@else
    <span
        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-600 text-[10px] font-bold leading-none text-white"
        aria-hidden="true"
    >{{ $utilisateur->initialeAvatar() }}</span>
@endif
