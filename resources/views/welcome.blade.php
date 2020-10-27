<x-layout>
    <div>
        <div class="w-full pt-5 mb-5">
            <solar-energy
                :goodwe-ids="{{ $goodweIds }}"
                api="{{ config('app.url') }}">
            </solar-energy>
        </div>

        <div class="w-full text-center mb-5">
            <weather-time weather-city="Hoofddorp"></weather-time>
        </div>

        <div class="w-full flex justify-center mb-5 pb-4">
            <live-chart
                initial-date="{{ now()->format('d-m-Y') }}"
                :data="{{ $liveGraphData }}">
            </live-chart>
        </div>

        <div class="flex max-w-sm sm:max-w-full mx-auto justify-center pt-12 pb-10">
            <weekly-graph></weekly-graph>
        </div>
    </div>
</x-layout>