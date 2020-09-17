@component('mail::message')
# Vandaag opgebracht
@foreach ($logs as $log)
* {{ $log->user }}: {{ $log->total_production / 1000 }} kWh
@endforeach

@component('mail::button', ['url' => 'https://sunnydays.johnny.digital'])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
