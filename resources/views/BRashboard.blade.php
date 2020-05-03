<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Brashboard</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <v-app id="app">
            <v-content>
                <reload-component></reload-component>
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <!-- for each client one Card component -->
                        @if(@isset($clients))
                            @foreach($clients as $client)
                                <v-flex>
                                    <card-component
                                    :client-name="'{{ $client->name }}'"
                                    :visit-count="'{{ $client->count_visits }}'"
                                    :login-count="'{{ $client->count_login }}'"
                                    ></card-component>
                                </v-flex>
                            @endforeach
                        @endif
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </body>
</html>





