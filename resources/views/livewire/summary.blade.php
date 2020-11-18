<div>
    <div class="w-full text-center mb-2">
        @foreach ($powerStations as $powerStation)
        <span style="color: rgba({{ $powerStation['owner']['color'] }});">
            &mdash; {{ $powerStation['owner']['name'] }}
        </span>
        @endforeach
    </div>

    <div wire:key="dashboard" class="sm:w-1/2 w-full sm:mx-auto flex items-center justify-center">
        <livewire:gauge
            title="Today"
            subtitle="kWh"
            property="today"
        />

        <livewire:gauge
            title="Now"
            subtitle="W"
            property="generating"
        />

        <livewire:gauge
            title="Total"
            subtitle="kWh"
            property="total"
        />

        <livewire:gauge
            title="Average"
            subtitle="kWh / day"
            property="average"
        />
    </div>
</div>
