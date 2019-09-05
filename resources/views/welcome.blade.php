<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tax Statistics</title>

        <!-- Fonts -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>

        <div class="container">
            <div class="p-5 text-center">
                <h1>Tax Statistics</h1>

                 <p class="lead mb-5">This is a development test, and a per requirement, this will display the statistics of tax income and tax rate per country, state and counties. The only difference is that the application can handle as many countries/states/counties as needed.</p>

                <p class="lead mb-5">Since the application is designed to handle multiple countries, it can also handle all (or most) kinds of tax computations that would be specific to a country (fixed rate, percentage rate, fixed+percentage rate, nomal bracketing, chuncked bracketing, etc), which is greatly considered when deremining the average tax rating. </p>

                <p class="lead mb-5">The database is seeded with 1,000,000 tax records and the server is configured using the lowest resources.</p>

                <p class="lead mb-5">Please check my email for the login information.</p>

                <a href="{{ route('home.dashboard') }}" class="btn btn-lg btn-success">Go To Dashboard</a>
            </div>


        </div>

    </body>
</html>
