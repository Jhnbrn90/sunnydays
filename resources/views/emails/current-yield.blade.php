@component('mail::message')
# Op dit moment

@foreach ($currentYields as $powerStationName => $yield)
* {{ $powerStationName }}: {{ $yield }} W
@endforeach

# Weer
{{ $weatherCondition['text'] }}, {{ $weatherCondition['temperature'] }} &deg;C

@component('mail::button', ['url' => config('app.url')])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
