@props(['name'])
{{-- Small full-colour illustrations of part categories (used on the home parts banner). --}}
@php
    $art = [
        'memory' => '
            <rect x="4" y="14" width="40" height="17" rx="1.6" fill="#1f7a4b"/>
            <rect x="4" y="14" width="40" height="3" rx="1.2" fill="#ffffff" opacity=".12"/>
            <rect x="7.5" y="18.5" width="6.5" height="9" rx=".8" fill="#17191d"/>
            <rect x="16" y="18.5" width="6.5" height="9" rx=".8" fill="#17191d"/>
            <rect x="25.5" y="18.5" width="6.5" height="9" rx=".8" fill="#17191d"/>
            <rect x="34" y="18.5" width="6.5" height="9" rx=".8" fill="#17191d"/>
            <rect x="5" y="31" width="20" height="4" rx=".6" fill="#e7b43f"/>
            <rect x="27" y="31" width="16" height="4" rx=".6" fill="#e7b43f"/>
            <path d="M7 31v4M10 31v4M13 31v4M16 31v4M19 31v4M22 31v4M30 31v4M33 31v4M36 31v4M39 31v4" stroke="#b9862a" stroke-width=".7"/>',
        'chip' => '
            <rect x="7" y="7" width="34" height="34" rx="3" fill="#2d6a4a"/>
            <path d="M10 10h2M15 10h2M20 10h2M25 10h2M30 10h2M35 10h2M10 38h2M15 38h2M20 38h2M25 38h2M30 38h2M35 38h2" stroke="#e7b43f" stroke-width="1.4" stroke-linecap="round"/>
            <rect x="12.5" y="12.5" width="23" height="23" rx="3" fill="#c9ced6"/>
            <rect x="12.5" y="12.5" width="23" height="11" rx="3" fill="#ffffff" opacity=".35"/>
            <rect x="16" y="20" width="10" height="1.6" rx=".8" fill="#7a818c"/>
            <rect x="16" y="24" width="15" height="1.6" rx=".8" fill="#9aa1ab"/>
            <circle cx="31" cy="16.5" r="1.3" fill="#7a818c"/>',
        'drive' => '
            <rect x="6" y="9" width="36" height="30" rx="4" fill="#2a2e35"/>
            <rect x="6" y="9" width="36" height="6" rx="3" fill="#ffffff" opacity=".1"/>
            <rect x="10" y="14" width="28" height="15" rx="2" fill="#eceef1"/>
            <rect x="10" y="14" width="28" height="4" rx="2" fill="#ff9919"/>
            <rect x="13" y="22" width="12" height="1.8" rx=".9" fill="#8a9099"/>
            <rect x="13" y="25.5" width="18" height="1.8" rx=".9" fill="#b3b8c0"/>
            <circle cx="10" cy="35" r="1.3" fill="#5b616b"/><circle cx="38" cy="35" r="1.3" fill="#5b616b"/>',
        'mouse' => '
            <path d="M24 6c-8 0-13 6-13 14v8c0 8 5.5 14 13 14s13-6 13-14v-8C37 12 32 6 24 6Z" fill="#2a2e35"/>
            <path d="M24 6c-8 0-13 6-13 14v1h26v-1C37 12 32 6 24 6Z" fill="#3a3f48"/>
            <path d="M24 6v15" stroke="#17191d" stroke-width="1.2"/>
            <rect x="22.4" y="10" width="3.2" height="7" rx="1.6" fill="#ff9919"/>
            <path d="M15 14c1.5-3.5 4.5-6 8-6.6" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" opacity=".25" fill="none"/>',
        'keyboard' => '
            <rect x="3" y="13" width="42" height="22" rx="3.5" fill="#2a2e35"/>
            <g fill="#4a505b">
                <rect x="6.5" y="16.5" width="4" height="3.5" rx=".8"/><rect x="12" y="16.5" width="4" height="3.5" rx=".8"/><rect x="17.5" y="16.5" width="4" height="3.5" rx=".8"/><rect x="23" y="16.5" width="4" height="3.5" rx=".8"/><rect x="28.5" y="16.5" width="4" height="3.5" rx=".8"/><rect x="34" y="16.5" width="7.5" height="3.5" rx=".8"/>
                <rect x="6.5" y="21.7" width="6" height="3.5" rx=".8"/><rect x="14" y="21.7" width="4" height="3.5" rx=".8"/><rect x="19.5" y="21.7" width="4" height="3.5" rx=".8"/><rect x="25" y="21.7" width="4" height="3.5" rx=".8"/><rect x="30.5" y="21.7" width="4" height="3.5" rx=".8"/><rect x="36" y="21.7" width="5.5" height="3.5" rx=".8"/>
                <rect x="6.5" y="27" width="5" height="3.5" rx=".8"/><rect x="36.5" y="27" width="5" height="3.5" rx=".8"/>
            </g>
            <rect x="13" y="27" width="22" height="3.5" rx=".8" fill="#ff9919"/>',
        'plug' => '
            <path d="M24 33v6c0 2.5 2 4 4.5 4H40" stroke="#2a2e35" stroke-width="2.4" stroke-linecap="round" fill="none"/>
            <rect x="13" y="9" width="22" height="25" rx="4.5" fill="#2a2e35"/>
            <rect x="13" y="9" width="22" height="7" rx="3.5" fill="#ffffff" opacity=".1"/>
            <rect x="18" y="4" width="2.6" height="6" rx="1" fill="#c9ced6"/><rect x="27.4" y="4" width="2.6" height="6" rx="1" fill="#c9ced6"/>
            <circle cx="24" cy="25" r="1.8" fill="#3fd17f"/>',
        'monitor' => '
            <rect x="4" y="8" width="40" height="26" rx="2.5" fill="#2a2e35"/>
            <rect x="6.5" y="10.5" width="35" height="20" rx="1.2" fill="#1d5ea8"/>
            <path d="M6.5 24 18 16l9 6 6-4 8.5 6v6.5h-35Z" fill="#3b86d6"/>
            <rect x="21" y="34" width="6" height="5" fill="#9aa1ab"/>
            <rect x="15" y="39" width="18" height="2.6" rx="1.3" fill="#c9ced6"/>',
        'battery' => '
            <rect x="5" y="14" width="34" height="20" rx="3.5" fill="#2a2e35"/>
            <rect x="39" y="20" width="4" height="8" rx="1.2" fill="#2a2e35"/>
            <rect x="8.5" y="17.5" width="6.5" height="13" rx="1.2" fill="#3fd17f"/><rect x="17" y="17.5" width="6.5" height="13" rx="1.2" fill="#3fd17f"/><rect x="25.5" y="17.5" width="6.5" height="13" rx="1.2" fill="#3fd17f"/>',
        'laptop' => '
            <rect x="8" y="9" width="32" height="21" rx="2" fill="#2a2e35"/>
            <rect x="10.5" y="11.5" width="27" height="16" rx="1" fill="#1d5ea8"/>
            <path d="M4 32h40l-2.5 5H6.5Z" fill="#9aa1ab"/><rect x="20" y="32" width="8" height="1.6" rx=".8" fill="#6c737e"/>',
        'box' => '
            <path d="M8 16 24 9l16 7v18l-16 7-16-7Z" fill="#c98f52"/>
            <path d="M8 16l16 7 16-7-16-7Z" fill="#e0aa6c"/>
            <path d="M24 23v18" stroke="#a87440" stroke-width="1.2"/>
            <path d="M16 12.5 32 19.5" stroke="#f3d3a8" stroke-width="3"/>',
    ];
@endphp
<svg {{ $attributes->merge(['viewBox' => '0 0 48 48', 'width' => 48, 'height' => 48, 'aria-hidden' => 'true', 'focusable' => 'false']) }}>{!! $art[$name] ?? $art['box'] !!}</svg>
