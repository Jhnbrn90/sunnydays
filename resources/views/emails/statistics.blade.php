@component('mail::message')
# Vandaag opgebracht
* J & L: {{ ($logs['JL']->total_production)/1000 }} kWh
* M & B: {{ ($logs['MB']->total_production)/1000 }} kWh
* B & E: {{ ($logs['BE']->total_production)/1000 }} kWh
* RB: {{ ($logs['RB']->total_production)/1000 }} kWh

@component('mail::button', ['url' => 'https://sunnydays.johnny.digital'])
Check de site
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
