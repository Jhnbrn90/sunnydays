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

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
    <link rel="stylesheet" href="css/weather-icons.css">

    <style>
        .LJ {
            color: rgb(255, 165, 120);
        }

        .JL {
            color: rgb(255, 165, 120);
        }

        .MB {
            color: rgb(2, 158, 227);
        }

        .BE {
            color: rgb(0, 153, 51)
        }

        .RB {
            color: rgb(95, 66, 244);
        }
    </style>
</head>

<body>

<div id="app">
    {{ $slot }}
</div>

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

</body>
</html>