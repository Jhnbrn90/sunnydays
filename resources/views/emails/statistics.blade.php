@component('mail::message')
# Vandaag opgebracht
@foreach ($logs as $user => $data)
* {{ $user }}: {{ $data->total_production / 1000 }} kWh
@endforeach

@component('mail::button', ['url' => 'https://sunnydays.johnny.digital'])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
