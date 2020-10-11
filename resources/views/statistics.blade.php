<x-layout>
    <div class="container">
        <div>
            <solar-energy :goodwe-ids="{{ $goodweIds }}" api="{{ config('app.url') }}"></solar-energy>
        </div>
    </div>
</x-layout>