<div>
    <h3 class="text-lg font-medium" wire:poll.10s>
        {{ $time }}
    </h3>

    <span class="block">
        {{ $date }}
    </span>

    <span>
        {{ $temperature }} &deg;C
    </span>

    <i class="wi wi-yahoo-{{ $iconClass }}"></i>
</div>