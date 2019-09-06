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

                 <p class="lead mb-5">This is a development test, and as per requirement, this will display the statistics of tax income and tax rate per country, state and county. The only difference is that the application can handle as many countries/states/counties as needed.</p>

                <p class="lead mb-5">Since the application is designed to handle multiple countries, it can also handle all (or most) kinds of tax computations that would be specific to a country (fixed rate, percentage rate, fixed+percentage rate, nomal bracketing, chuncked bracketing, etc), which is greatly considered when deremining the average tax rating. </p>

                <p class="lead mb-5">While the application provided a CRUD, default country, states and counties is seeded already. The database is also seeded with 1,000,000 tax records and the server is configured using the lowest resources to demonstrate the efficiency and speed of the application.</p>

                <p class="lead mb-5">Please check my email for the login information. Or if you have any trouble accessing any part of the application, please don't hesitate to contact me.</p>

                <a href="{{ route('home.dashboard') }}" class="btn btn-lg btn-success">Go To Dashboard</a>
            </div>


        </div>

    </body>
</html>
 pa
