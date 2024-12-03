@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'DUA')
<img src="{{asset('storage/logo_light.png')}}" class="logo" alt="DUA Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
