<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="Sunny Days">
    <meta name="application-name" content="Sunny Days">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>Sunny Days🌤 </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/weather-icons.css">

    <style>
        .LJ {
            color: rgb(255, 165, 120);
        }

        .MB {
            color: rgb(2, 158, 227);
        }
    </style>

    <!-- Scripts -->

</head>

<body>

    <div id="app">

        <div class="container">
            <div class="LJ">
                <solar-energy goodwe-id="{{ $goodweIds['JL'] }}" api="{{ config('app.url') }}"></solar-energy>
            </div>

            <div class="MB">
                <solar-energy goodwe-id="{{ $goodweIds['MB'] }}" api="{{ config('app.url') }}"></solar-energy>
            </div>
        </div>

        <center>
            <weather-time weather-city="Hoofddorp"></weather-time>
        </center>

        <center>
            <daily-graph></daily-graph>
        </center>


        <center>
            <weekly-graph></weekly-graph>
        </center>

    </div>

    <script type="text/javascript" src="js/app.js"></script>

</body>

</html>