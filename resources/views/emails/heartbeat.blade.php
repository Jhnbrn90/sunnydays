@component('mail::message')
# Op dit moment 
* J & L: {{ $values['JL'] }} W
* M & B: {{ $values['MB'] }} W
* B & E: {{ $values['BE'] }} W
* RB: {{ $values['RB'] }} W

# Weer
{{ $weather['condition'] }}, {{ $weather['temperature'] }} &deg;C

@component('mail::button', ['url' => 'https://sunnydays.johnny.digital'])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
