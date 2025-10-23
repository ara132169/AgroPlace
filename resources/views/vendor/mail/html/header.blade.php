@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://agro-marketmx.com/images/site/LOGO_68095f900d262.jpg" class="logo" alt="Agro MarketPlace Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
