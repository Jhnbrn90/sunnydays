<x-layout>
    <div class="container">
        <div class="w-full mb-4">
            <solar-energy
                :goodwe-ids="{{ $goodweIds }}"
                api="{{ config('app.url') }}"
            />
        </div>

        <div class="w-full text-center mt-4">
            <weather-time weather-city="Hoofddorp"/>
        </div>

        <center>
            <live-chart initial-date="{{ now()->format('d-m-Y') }}" :data="{{ $liveGraphData }}"/>
        </center>

        <center>
            <weekly-graph></weekly-graph>
        </center>
    </div>
</x-layout>