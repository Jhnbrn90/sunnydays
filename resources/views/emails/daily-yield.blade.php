@component('mail::message')
# Vandaag opgebracht
@foreach ($logs as $log)
* {{ $log->powerStation->name }}: {{ $log->total_production / 1000 }} kWh
@endforeach

@component('mail::button', ['url' => config('app.url')])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
