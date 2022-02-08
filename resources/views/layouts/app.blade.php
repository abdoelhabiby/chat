<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


   <link rel="stylesheet" href="{{asset('line-awesome-1.3.0/css/line-awesome.min.css')}}">


  <style>
        .number {
            height: 27px;
            width: 27px;
            background-color: #d63031;
            border-radius: 20px;
            color: white;
            text-align: center;
            position: absolute;
            left: 25px;
            top: 2px;
            padding: 0px;
            border-style: solid;
            border-width: 2px;
        }

    </style>

<script src="{{asset('js/jquery-3.4.1.min.js')}}" ></script>


    @yield('style')



</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdownNotifiction" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="lar la-bell" style="font-size: 24px;"></i> <span class="number"
                                        id="notifications_number"
                                        style="">{{ auth()->user()->unreadNotifications->count() }}</span>

                                </a>

                                <div class="dropdown-menu dropdown-menu-right" style=""
                                    aria-labelledby="navbarDropdownNotifiction">

                                    @php
                                        $user_notifications = auth()->user()->notifications->take(5);
                                    @endphp


                                    @if ($user_notifications->count() > 0)

                                    @foreach ($user_notifications as $notification)

                                        <a class="dropdown-item d-flex" href="" style="{{$notification->read_at ? '' : 'background-color: #DDD'}}">
                                            <img src="{{ asset('images/user_default.png') }}" alt="" width="30"
                                                height="30px" class="rounded-circle ">
                                            <div class="ml-1">
                                                <p class=" ">
                                                    {{$notification->data['notifiction_text']}}
                                                    <br>
                                                    <span class="">{{\Carbon\Carbon::parse($notification->created_at)->toDayDateTimeString()}}</span>
                                                </p>

                                            </div>
                                        </a>
                                        @if(!$loop->last)
                                        <hr>
                                        @endif



                                    @endforeach




                                    @else

                                        empty

                                    @endif






                                </div>
                            </li>





                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script> --}}

    {{-- <script src="{{asset('js/jquery-3.4.1.min.js')}}" ></script> --}}


    @auth
        <script>
            $(function() {

                var notifications_number = $("#notifications_number");

                var channel_name_n = "channel_new_notification." + "{{ auth()->user()->id }}";

                window.Echo.private(channel_name_n)
                    .listen('NotificationPostNewCommentEvent', (e) => {
                        var count_nto = e.notifications_unread_count;
                        notifications_number.text(count_nto);
                    });





            });
        </script>
    @endauth


    @yield('websocket')
    @yield('js')






</body>

</html>
