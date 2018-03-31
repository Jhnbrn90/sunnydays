<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sunny Days🌤 </title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="css/app.css">
        <link rel="stylesheet" href="css/weather-icons.css">

        <!-- Scripts -->

    </head>
    <body>

    <div class="container">

        <div id="app">
            <div class="container">
                <solar-energy goodwe-id="{{ $goodweId }}" api="{{ config('app.url') }}"></solar-energy>
                <weather-time weather-city="Hoofddorp"></weather-time>

                <daily-graph :data="{{ $data }}"></daily-graph>

            </div>
        </div>

    <script type="text/javascript" src="js/app.js"></script>

    </body>

</html>
