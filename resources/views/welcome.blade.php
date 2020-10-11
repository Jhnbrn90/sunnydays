<x-layout>
    <div class="container">
        <div>
            <solar-energy :goodwe-ids="{{ $goodweIds }}" api="{{ config('app.url') }}"></solar-energy>
        </div>
    </div>

    <center style="margin-top: 60px;">
        <weather-time weather-city="Hoofddorp"></weather-time>
    </center>

    <center>
        <daily-graph></daily-graph>
    </center>

    <center>
        <weekly-graph></weekly-graph>
    </center>
</x-layout>