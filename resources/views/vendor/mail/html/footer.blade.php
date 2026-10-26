<tr>
<td class="footer" style="padding: 20px; text-align: center;">
    <p style="color: rgba(15,23,42,.55); font-size: 12px; line-height: 1.5; margin: 0;">
        © {{ date('Y') }} <strong>{{ config('clinic.name') }}</strong><br>
        @if(config('clinic.address')){{ config('clinic.address') }}<br>@endif
        If you didn’t request this email, you can ignore it.
    </p>
</td>
</tr>
