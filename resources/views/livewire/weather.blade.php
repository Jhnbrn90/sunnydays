<div>
    <h3 class="text-lg font-medium" wire:poll.2s>
        {{ $time }}
    </h3>

    <span class="block">
        {{ $date }}
    </span>

    <span>
        {{ $temperature }} &deg;C
    </span>

    <img class="inline-block" width="50px" height="50px" src="{{ $iconUrl }}">
</div>