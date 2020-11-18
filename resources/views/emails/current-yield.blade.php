@component('mail::message')
# Op dit moment 
* J & L: {{ $currentYields['JL'] }} W
* M & B: {{ $currentYields['MB'] }} W
* B & E: {{ $currentYields['BE'] }} W
* RB: {{ $currentYields['RB'] }} W

# Weer
{{ $weatherCondition['text'] }}, {{ $weatherCondition['temperature'] }} &deg;C

@component('mail::button', ['url' => 'https://sunnydays.johnny.digital'])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
