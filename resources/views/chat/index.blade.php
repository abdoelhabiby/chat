@extends('layouts.app')


@section('style')

    <link href="{{ asset('css/chat_app.css') }}" rel="stylesheet">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" /> --}}


@endsection

@section('content')

    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">

                            @if ($friends->count() > 0)

                                @foreach ($friends as $friend)

                                    <li class="clearfix" id="friend_id_{{ $friend->id }}"
                                        data-friend-id="{{ $friend->id }}">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                                        <div class="about">
                                            <div class="name">{{ $friend->name }}</div>
                                            <div class="status">
                                                <i
                                                    class="las la-circle  {{ $friend->online == 1 ? 'online' : 'offline' }}"></i>
                                                <span class="text_status">
                                                    {{ $friend->online == 1 ? 'online' : '' }}
                                                    {{ $friend->online == 0 ? \Carbon\Carbon::createFromDate($friend->last_online)->diffForHumans() : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>



                                @endforeach

                            @endif


                            <li class="clearfix active">
                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Aiden Chavez</div>
                                    <div class="status">
                                        <i class="las la-circle offline "></i>
                                        2 hours ago
                                    </div>
                                </div>
                            </li>


                        </ul>
                    </div>
                    <div class="chat">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                    </a>
                                    <div class="chat-about">
                                        <h6 class="m-b-0">Aiden Chavez</h6>
                                        <small>Last seen: 2 hours ago</small>
                                    </div>
                                </div>
                                <div class="col-lg-6 hidden-sm text-right">
                                    <a href="javascript:void(0);" class="btn btn-outline-secondary"><i
                                            class="las la-camera" style="font-size: 17px;"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="las la-image"
                                            style="font-size: 17px;"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-info"><i class="las la-cogs"
                                            style="font-size: 17px;"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-danger"><i class="las la-question"
                                            style="font-size: 17px;"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="chat-history">
                            <ul class="m-b-0">
                                <li class="clearfix">
                                    <div class="message-data text-right">
                                        <span class="message-data-time">10:10 AM, Today</span>
                                        {{-- <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar"> --}}
                                    </div>
                                    <div class="message my-message float-right"> Lorem Ipsum is simply dummy text of the
                                        printing and typesetting industry. Lorem Ipsum has been the industry </div>


                                </li>





                                <li class="clearfix">
                                    <div class="message-data ">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                        <span class="message-data-time">10:10 AM, Today</span>

                                    </div>
                                    <div class="message other-message "> Lorem Ipsum is simply dummy text of the printing
                                        and typesetting industry. Lorem Ipsum has been the industry
                                    </div>
                                </li>







                            </ul>
                        </div>
                        <div class="chat-message clearfix">
                            <div class="input-group mb-0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="lar la-paper-plane"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Enter text here...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('websocket')

    <script>
        $(function() {



                           var channel_name = "chat_room";

                            window.Echo.private(channel_name)
                            .listen('NewMessageEvent', (user) => {

                                console.log('test again')

                            });



            var tet = "{{ auth()->user()->id }}";

            var ChatRoomName = '';

            window.Echo.join('chat')
                .here((users) => {})
                .joining((user) => {
                    console.log('joining now : ' + user.name);
                    var url = '/api/chat/user/' + user.id + '/online';
                    data = {
                        api_token: user.api_token,
                        my: tet
                    };
                    $.ajax({
                        type: "PUT",
                        url: url,
                        data: data,
                    });

                    var friend_list = $(document).find(".chat-list #friend_id_" + user.id);

                    if (friend_list.length) {

                        friend_list.find(" .la-circle").removeClass('offline');
                        friend_list.find(" .la-circle").addClass('online');
                        friend_list.find('.text_status').text('online');
                    }


                })

                .leaving((user) => {
                    console.log('leving now : ' + user.name);

                    var friend_list = $(document).find(".chat-list #friend_id_" + user.id);

                    if (friend_list.length) {

                        friend_list.find(" .la-circle").removeClass('online');
                        friend_list.find(" .la-circle").addClass('offline');
                        friend_list.find('.text_status').text('3 seconds ago');
                    }

                })
                .listen('UserOnlineEvent', (e) => {

                    if (e.user.id != tet) {
                        console.log(' UserOnlineEvent : ' + e.user.name);

                        var friend_list = $(document).find(".chat-list #friend_id_" + e.user.id);

                        friend_list.find(" .la-circle").removeClass('offline');
                        friend_list.find(" .la-circle").addClass('online');
                        friend_list.find('.text_status').text('online');

                    }

                });




            $(document).on('click', '.chat-list li', function(e) {
                e.preventDefault();
                var get_id = $(this).data('friend-id');
                // ChatRoomName = 'ChatRoom_' + tet + '_' + get_id;

                var url = '/api/chat/user/' + get_id + '/offline';
                var api_token = '4zFNuMESKYAlbcTEPDdqBINfkjEqQUNqpU9FPCM8mMcQADnnSs';
                data = {
                    api_token: api_token,
                    my: tet
                };
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: data,
                    success: function(su) {
                        console.log(su);
                    }

                });

                // ChatRoomName = testCahngeValue('new');

                console.log();
            });


            function testCahngeValue(valuee) {
                return valuee;
            };


            // --------------------------------------









        });
    </script>

@stop
