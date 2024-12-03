<x-mail::message>
## Your Group Login Links

Click the links below to login to {{ config('app.name') }}.

This links will expire in 30 minutes.

<ol>
@foreach ($urls as $url)
<li><a href="{{$url}}">{{$url}}</a></li>
@endforeach
</ol>

<small>If you didn't request this email, you can safely ignore it.</small>
</x-mail::message>
