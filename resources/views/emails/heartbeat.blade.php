<body>
    <div style="text-align:center;">
        <h3>Huidige statistieken</h3>
        <img width="350" src="{{ $message->embed(public_path('images/statistics.png')) }}">
        <br>
        <strong>Weer</strong>: {{ $weather['text'] }}, {{ $weather['temperature'] }} &deg;C
        <br>
        <img src="{{ $message->embed(public_path('images/graph.png')) }}">
    </div>
</body>