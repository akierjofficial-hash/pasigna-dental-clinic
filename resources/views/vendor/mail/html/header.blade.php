<tr>
<td class="header" style="padding: 25px 0; text-align: center;">
    @php
        $logoUrl = asset(config('clinic.logo'));
    @endphp

    <a href="{{ $url }}" style="display:inline-flex;align-items:center;gap:12px;text-decoration:none;">
        <img src="{{ $logoUrl }}" alt="" width="46" height="46" style="display:block;border-radius:12px;object-fit:contain;background:#fff;">

        <span style="display:flex;flex-direction:column;align-items:flex-start;gap:2px;">
            <span style="font-size:16px;font-weight:900;color:#3c1e5e;line-height:1.15;">{{ config('clinic.name') }}</span>
        </span>
    </a>
</td>
</tr>
