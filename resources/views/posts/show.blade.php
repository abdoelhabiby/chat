@extends('layouts.app')


@section('websocket')

    <script>
        $(function() {



                            var channel_name = "channelNewComment_" + "{{ $post->id }}";
                            var image = "{{ asset('images/user_default.png') }}";

                            window.Echo.channel(channel_name)
                            .listen('NewCommentEvent', (new_comment) => {
                                // console.log(new_comment);
                                var user_id = "{{ auth()->user()->id }}"
                                var check_his_comment = user_id == new_comment.comment.user_id;
                                var button_de = "";

                                if (check_his_comment) {
                                    button_de =
                                        `<button id="delete_comment" class="btn btn-danger "
                                data-action="/post/${new_comment.comment.post_id}/comments/${new_comment.comment.id}">delete</button>`;
                                }

                                var add_comment = `<div class="comment" id="comment_${new_comment.comment.id}">


                            <div class="d-flex">
                                <img src="${image}" alt="" width="50" height="50px"
                                    class="rounded-circle align-self-start">
                                <p class="align-self-center ml-2">${ new_comment.user.name }</p>
                            </div>

                            <span>${new_comment.comment.created_at}</span>
                            <p class="comment_text mt-2">${new_comment.comment.comment}</p> ` + button_de + `
                            <hr>
                            </div>`;

                                $('.comments').append(add_comment);


                            });

                        })
    </script>

@endsection

@section('content')







    <div class="container" id='app'>


        <div class="card mb-3" style="">
            <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p class="card-text">{{ $post->post }}</p>
            </div>
        </div>


        <div class="card mb-3" style="">

            <div class="card-body">
                <h5 class="card-title float-left">comments</h5>
                <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                    data-target="#modal_create_comment" data-whatever="@mdo">
                    add comment
                </button>

                <div class="clearfix"></div>
                <hr>


                <div class="comments">
                    @if ($post->comments->count() > 0)

                        @foreach ($post->comments as $comment)


                            <div class="comment" id="comment_{{ $comment->id }}">


                                <div class="d-flex">
                                    <img src="{{ asset('images/user_default.png') }}" alt="" width="50" height="50px"
                                        class="rounded-circle align-self-start">
                                    <p class="align-self-center ml-2">{{ $comment->user->name }}</p>

                                </div>


                                <p class="comment_text mt-2">{{ $comment->comment }}
                                    <br>
                                    <span>{{ \Carbon\Carbon::parse($comment->created_at)->toDayDateTimeString()}}</span>
                                </p>

                                @if (auth()->user()->id == $comment->user->id)
                                    <button id="delete_comment" class="btn btn-danger "
                                        data-action="{{ route('comments.destroy', [$post->id, $comment->id]) }}">delete</button>
                                @endif
                                <hr>
                            </div>
                        @endforeach
                    @endif
                </div>




            </div>



        </div>

    </div>





    <div class="modal fade" id="modal_create_comment" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="form_add_comment" method="post" action="{{ route('comments.store', $post->id) }}">

                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">

                    <div class="modal-body">



                        <div class="form-group">

                            <label for="message-text" class="col-form-label">Comment:</label>
                            <textarea class="form-control" id="message-text" name="comment"></textarea>

                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




@endsection





@section('js')



    <script>
        $(function() {


            // window.Echo.channel('channelNewComment')
            //     .listen('NewCommentEvent', (event) => {
            //         console.log(event);
            //     });




            $("#form_add_comment").submit(function(e) {
                e.preventDefault()
                var form_data = $(this).serialize();
                var action = $(this).attr('action');
                var image = "{{ asset('images/user_default.png') }}";

                $.ajax({
                    url: action,
                    method: 'post',
                    data: form_data,
                    beforeSend: function() {

                    },
                    success: function(success) {

                        console.log('sucess create');
                        $('#modal_create_comment').modal('hide');
                        $('#form_add_comment')[0].reset();
                        var add_comment = `<div class="comment" id="comment_${success.comment.id}">


                                <div class="d-flex">
                                    <img src="${image}" alt="" width="50" height="50px"
                                        class="rounded-circle align-self-start">
                                    <p class="align-self-center ml-2">${ success.user.name }</p>
                                </div>

                                <span>${success.comment.created_at}</span>
                                <p class="comment_text mt-2">${success.comment.comment}</p>
                                <button id="delete_comment" class="btn btn-danger "
                                    data-action="/post/${success.comment.post_id}/comments/${success.comment.id}">delete</button>

                                <hr>
                                </div>`;

                        $('.comments').append(add_comment);


                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });


            //-----------------------------------------------


            $(document).on('click', '#delete_comment', function(e) {
                var action = $(this).data('action');
                var token = $('meta[name="csrf-token"]').attr('content');
                var div_commnet = $(this).parent(".comment");


                $.ajax({
                    url: action,
                    data: {
                        _token: token
                    },
                    method: 'delete',
                    beforeSend: function() {

                    },
                    success: function(success) {
                        div_commnet.remove();

                        console.log('success delete');

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });


            });
        });
    </script>



@endsection
