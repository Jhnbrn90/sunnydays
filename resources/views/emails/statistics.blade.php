<body>

<h1>Dagelijks overzicht</h1>
<ul>
@foreach ($logs as $log)
    <li>
        {{ $log->user }}: {{ $log->total_production / 1000 }} kWh
    </li>
@endforeach
</ul>

<div style="text-align:center;">
    <h2>Opbrengst</h2>
    <img src="{{ $message->embed(public_path('images/weekly.png')) }}">

    <h2>Grafiek</h2>
    <img src="{{ $message->embed(public_path('images/graph.png')) }}">
</div>

</body>

