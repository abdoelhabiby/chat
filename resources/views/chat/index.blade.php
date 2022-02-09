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
                    <div id="plist" class="people-list" style="    background: white;">
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
                                        data-friend-id="{{ $friend->id }}" data-friend-name="{{ $friend->name }}"
                                        data-friend-status="{{ $friend->online }}"
                                        data-friend-last-online="{{ $friend->online == 0 ? \Carbon\Carbon::createFromDate($friend->last_online)->diffForHumans() : '' }}">
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




                        </ul>
                    </div>


                    <div class="chat d-none">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="chat-header-image">

                                    </div>


                                    <div class="chat-about">
                                        <h6 class="m-b-0 friend_name"></h6>
                                        <span class="status"></span>
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



                        <div class="chat-text" style="max-height: 300px;overflow:auto;min-height:300px">

                            <div class="chat-history">




                                <ul class="m-b-0">

                                </ul>
                            </div>


                        </div>





                        <div class="chat-message clearfix">



                        </div>
                        <div id="target"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('websocket')

    <script src="{{ asset('js/chat.js') }}"></script>
    <script>
        $(function() {





            $(document).on('click', '.chat-list li', function(e) {
                e.preventDefault();

                $('.chat .chat-text .chat-history ul').empty();
                var friend_id = $(this).data('friend-id');
                var image = "https://bootdey.com/img/Content/avatar/avatar2.png";
                var the_list = this;
                var api_token = "{{ auth()->user()->api_token }}";

                //will need it multi  hhhhhhhhhhh

                localStorage.setItem('api_token', api_token);
                localStorage.removeItem('next_page_url');
                localStorage.removeItem('friend_id');


                $(".chat-message").html(' ');

                var url = "/api/chat/conversation/" + friend_id;
                data = {
                    "api_token": api_token
                };


                $.ajax({
                    url,
                    data,
                    success: function(data) {
                        $(document).find('.chat-list li').removeClass('active');
                        $(the_list).addClass('active');

                        $('.chat').removeClass('d-none');
                        $('.spinner_').remove();

                        localStorage.setItem('friend_id', data.friend.id);



                        changeHeaderCaht(data.friend.name, image, data.friend.online, data
                            .friend.last_online);
                        var api_token = "{{ auth()->user()->api_token }}";
                        var url_fetch_message = "/api/chat/fetch_messages/" + friend_id;
                        fetchChatMessage(friend_id, api_token, url_fetch_message);
                        var conversation_channel = 'conversation.' + data.conversation_id;

                        var form_url =
                            `api/chat/conversation/${data.conversation_id}/${data.friend.id}`;

                        appendFormMessage(form_url, api_token);

                        //send request to make messages as seen




                        var url_conversation_messages_seen = `api/chat/conversation/${data.conversation_id}/${data.friend.id}/seen`
                        requestChatMessageMakeSeen(api_token ,url_conversation_messages_seen);



                        window.Echo.join(conversation_channel)
                            .listen('NewMessageEvent', (data) => {

                                appendNewMessageFriend(data.text_message, image, data
                                    .created_at);
                                    requestChatMessageMakeSeen(api_token ,url_conversation_messages_seen);

                            }).listen('ConversationMessagesAsSeenEvent', (data) => {

                               makeMssagesAsSeen();

                            });


                            var objDiv = $(document).find('.chat-text');
                            var h = objDiv.get(0).scrollHeight;
                            objDiv.animate({scrollTop: h});







                    },
                    error: function(errors) {
                        console.log(errors);
                    }

                });






            });









            //-------------------------------------------------

            var user_id = "{{ auth()->user()->id }}";

            var ChatRoomName = 'chat';

            window.Echo.join(ChatRoomName)
                .here((users) => {})
                .joining((user) => {
                    console.log('joining now : ' + user.name);
                    var url = '/api/chat/user/' + user.id + '/online';
                    data = {
                        api_token: user.api_token,
                        my: user_id
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

                    if (e.user.id != user_id) {
                        console.log(' UserOnlineEvent : ' + e.user.name);

                        var friend_list = $(document).find(".chat-list #friend_id_" + e.user.id);

                        friend_list.find(" .la-circle").removeClass('offline');
                        friend_list.find(" .la-circle").addClass('online');
                        friend_list.find('.text_status').text('online');

                    }

                });























            // --------------------------------------









        });
    </script>

@stop
