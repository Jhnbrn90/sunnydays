<div class="flex-1 flex justify-center" wire:poll.2s>
    <div>
        <div class="py-2 px-4 sm:p-4 text-center">
            <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
                {{ $title }}
            </span>

            <span class="font-hairline">
                {{ $subtitle }}
            </span>

            @foreach ($powerStations as $powerStation)
            <div class="sm:text-2xl text-xl">
                <span class="font-normal sm:font-hairline" style="color: rgb({{ $powerStation['owner']['color'] }});">
                    {{ $powerStation[$property] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>