Error on {{ env('APP_NAME') }}
<b>Description: </b> <code>{{ $description }}</code>
<b>File: </b> <code>{{ $file }}</code>
<b>Line: </b> <code>{{ $line }}</code>
@if(Auth::user())
    <b>User: </b> <a href="t.me/{{Auth::user()->telegram_username}}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
@endif
