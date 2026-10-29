@props(['name'])
{{--
    Realistic-style product renders for the home "Parts & accessories" section (drawn in-house, so they always
    look right even when the admin has no part photos). Names: ram, ssd, charger, mouse. Gradient ids are
    prefixed with the name, so each render can appear once per page.
--}}
@php
    $art = [
        'ram' => '
            <defs>
                <linearGradient id="ram-pcb" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#2c8a5a"/><stop offset="1" stop-color="#155a36"/></linearGradient>
                <linearGradient id="ram-chip" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#3a3f47"/><stop offset="1" stop-color="#121418"/></linearGradient>
                <linearGradient id="ram-gold" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f7dc8e"/><stop offset="1" stop-color="#c38e28"/></linearGradient>
                <linearGradient id="ram-label" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#f4f5f7"/><stop offset="1" stop-color="#cfd3d9"/></linearGradient>
            </defs>
            <ellipse cx="100" cy="118" rx="74" ry="7" fill="#14161a" opacity=".12"/>
            <g transform="rotate(-9 100 72)">
                <path d="M22 44h156a4 4 0 0 1 4 4v42h-73v-3a4 4 0 0 0-8 0v3H18V48a4 4 0 0 1 4-4Z" fill="url(#ram-pcb)"/>
                <path d="M22 44h156a4 4 0 0 1 4 4v3H18v-3a4 4 0 0 1 4-4Z" fill="#fff" opacity=".18"/>
                <circle cx="18" cy="66" r="4" fill="#f4f5f7"/><circle cx="182" cy="66" r="4" fill="#f4f5f7"/>
                <rect x="30" y="54" width="24" height="22" rx="2" fill="url(#ram-chip)"/>
                <rect x="60" y="54" width="24" height="22" rx="2" fill="url(#ram-chip)"/>
                <rect x="30" y="54" width="24" height="4" rx="1.5" fill="#fff" opacity=".12"/>
                <rect x="60" y="54" width="24" height="4" rx="1.5" fill="#fff" opacity=".12"/>
                <rect x="92" y="52" width="80" height="26" rx="3" fill="url(#ram-label)"/>
                <rect x="98" y="58" width="34" height="4" rx="2" fill="#7d8391"/>
                <rect x="98" y="66" width="50" height="3" rx="1.5" fill="#a3a9b3"/>
                <rect x="156" y="57" width="10" height="10" rx="2" fill="#ff9919"/>
                <g fill="url(#ram-gold)">
                    <rect x="22" y="82" width="4" height="8"/><rect x="29" y="82" width="4" height="8"/><rect x="36" y="82" width="4" height="8"/><rect x="43" y="82" width="4" height="8"/><rect x="50" y="82" width="4" height="8"/><rect x="57" y="82" width="4" height="8"/><rect x="64" y="82" width="4" height="8"/><rect x="71" y="82" width="4" height="8"/><rect x="78" y="82" width="4" height="8"/><rect x="85" y="82" width="4" height="8"/><rect x="92" y="82" width="4" height="8"/>
                    <rect x="112" y="82" width="4" height="8"/><rect x="119" y="82" width="4" height="8"/><rect x="126" y="82" width="4" height="8"/><rect x="133" y="82" width="4" height="8"/><rect x="140" y="82" width="4" height="8"/><rect x="147" y="82" width="4" height="8"/><rect x="154" y="82" width="4" height="8"/><rect x="161" y="82" width="4" height="8"/><rect x="168" y="82" width="4" height="8"/><rect x="175" y="82" width="4" height="8"/>
                </g>
            </g>',
        'ssd' => '
            <defs>
                <linearGradient id="ssd-body" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#4a505a"/><stop offset="1" stop-color="#1c1f24"/></linearGradient>
                <linearGradient id="ssd-top" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#5b626d"/><stop offset=".55" stop-color="#2d3138"/><stop offset="1" stop-color="#22252b"/></linearGradient>
                <linearGradient id="ssd-shine" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#fff" stop-opacity="0"/><stop offset=".5" stop-color="#fff" stop-opacity=".16"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></linearGradient>
            </defs>
            <ellipse cx="100" cy="120" rx="70" ry="7" fill="#14161a" opacity=".14"/>
            <g transform="rotate(-8 100 72)">
                <rect x="34" y="30" width="132" height="86" rx="9" fill="url(#ssd-body)"/>
                <rect x="34" y="30" width="132" height="80" rx="9" fill="url(#ssd-top)"/>
                <rect x="34" y="30" width="132" height="80" rx="9" fill="none" stroke="#fff" stroke-opacity=".14"/>
                <path d="M40 34h60l-30 72H40Z" fill="url(#ssd-shine)"/>
                <rect x="46" y="44" width="108" height="40" rx="4" fill="#15171b"/>
                <rect x="46" y="44" width="5" height="40" rx="2" fill="#ff9919"/>
                <text x="60" y="70" fill="#f2f3f5" font-family="Nunito, Segoe UI, Arial, sans-serif" font-size="17" font-weight="800" letter-spacing="2">SSD</text>
                <rect x="106" y="58" width="38" height="4" rx="2" fill="#5b626d"/>
                <rect x="106" y="66" width="26" height="4" rx="2" fill="#5b626d"/>
                <circle cx="44" cy="100" r="2.5" fill="#15171b"/><circle cx="156" cy="100" r="2.5" fill="#15171b"/>
            </g>',
        'charger' => '
            <defs>
                <linearGradient id="chg-body" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#3d424b"/><stop offset="1" stop-color="#14161a"/></linearGradient>
                <linearGradient id="chg-shine" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff" stop-opacity=".22"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></linearGradient>
                <linearGradient id="chg-metal" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#e8eaee"/><stop offset="1" stop-color="#9aa1ab"/></linearGradient>
            </defs>
            <ellipse cx="100" cy="122" rx="78" ry="7" fill="#14161a" opacity=".12"/>
            <path d="M58 66c-26 0-38 10-38 26s14 22 30 22" fill="none" stroke="#1d2025" stroke-width="5" stroke-linecap="round"/>
            <path d="M142 66c22 0 36 8 36 22 0 10-6 16-14 18" fill="none" stroke="#1d2025" stroke-width="5" stroke-linecap="round"/>
            <rect x="44" y="108" width="14" height="10" rx="3" fill="#1d2025"/><rect x="38" y="111" width="8" height="4" rx="1.5" fill="url(#chg-metal)"/>
            <rect x="158" y="102" width="16" height="11" rx="3" fill="#1d2025" transform="rotate(-20 166 107)"/>
            <rect x="56" y="40" width="88" height="52" rx="12" fill="url(#chg-body)"/>
            <rect x="56" y="40" width="88" height="24" rx="12" fill="url(#chg-shine)"/>
            <rect x="56" y="40" width="88" height="52" rx="12" fill="none" stroke="#fff" stroke-opacity=".1"/>
            <rect x="70" y="58" width="40" height="3" rx="1.5" fill="#5b626d"/>
            <rect x="70" y="65" width="28" height="3" rx="1.5" fill="#5b626d"/>
            <circle cx="128" cy="76" r="3.2" fill="#3fd17f"/><circle cx="128" cy="76" r="7" fill="#3fd17f" opacity=".18"/>',
        'mouse' => '
            <defs>
                <linearGradient id="mse-body" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#4a505a"/><stop offset=".6" stop-color="#22252b"/><stop offset="1" stop-color="#15171b"/></linearGradient>
                <radialGradient id="mse-shine" cx=".35" cy=".25" r=".6"><stop offset="0" stop-color="#fff" stop-opacity=".28"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></radialGradient>
                <linearGradient id="mse-wheel" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#9aa1ab"/><stop offset="1" stop-color="#3a3f47"/></linearGradient>
            </defs>
            <ellipse cx="100" cy="122" rx="46" ry="7" fill="#14161a" opacity=".16"/>
            <g transform="rotate(14 100 70)">
                <path d="M100 14c-24 0-38 18-38 44v20c0 26 16 42 38 42s38-16 38-42V58c0-26-14-44-38-44Z" fill="url(#mse-body)"/>
                <path d="M100 14c-24 0-38 18-38 44v20c0 26 16 42 38 42s38-16 38-42V58c0-26-14-44-38-44Z" fill="url(#mse-shine)"/>
                <path d="M100 16v42M64 58h72" stroke="#0d0f12" stroke-width="1.6" opacity=".7"/>
                <rect x="96" y="26" width="8" height="18" rx="4" fill="url(#mse-wheel)"/>
                <path d="M100 14c-24 0-38 18-38 44v20c0 26 16 42 38 42s38-16 38-42V58c0-26-14-44-38-44Z" fill="none" stroke="#fff" stroke-opacity=".12"/>
                <rect x="96" y="96" width="8" height="3" rx="1.5" fill="#ff9919"/>
            </g>',
    ];
@endphp
<svg {{ $attributes->merge(['viewBox' => '0 0 200 132', 'aria-hidden' => 'true', 'focusable' => 'false']) }}>{!! $art[$name] ?? '' !!}</svg>
