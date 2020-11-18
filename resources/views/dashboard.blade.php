<x-layout>
    <div>
        <div class="w-full pt-5 mb-5">
            <livewire:summary />
        </div>

        <div class="w-full text-center mb-5">
            <livewire:weather />
        </div>

        <div class="w-full flex justify-center mb-5 pb-4">
            <live-chart
                initial-date="{{ now()->format('d-m-Y') }}"
                :data="{{ $liveChartData }}">
            </live-chart>
        </div>

        <div class="flex max-w-sm sm:max-w-full mx-auto justify-center pt-12 pb-10">
            <week-chart
                :data="{{ $weekChartData }}"
            ></week-chart>
        </div>
    </div>
</x-layout>