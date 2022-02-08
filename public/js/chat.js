function fetchChatMessage(friend_id, api_token, url) {

    var url = url;
    var friend_image = "https://bootdey.com/img/Content/avatar/avatar2.png";
    data = {
        "api_token": api_token,
    };

    $.ajax({
        type: "get",
        url: url,
        data: data,
        success: function(data) {



            if (data.chat_messages && data.chat_messages.length > 0) {

                if (data.pagination_details.next_page_url != null) {
                    console.log(data.pagination_details.next_page_url);

                    localStorage.setItem('next_page_url', data.pagination_details.next_page_url);

                    appendSpinner();
                } else {
                    localStorage.removeItem('next_page_url')
                }


                var messages_html = ``;
                $.each(data.chat_messages, function(key, value) {

                    if (value.sender_id == friend_id) {
                        var li_text = `  <li class="clearfix">
                                    <div class="message-data ">
                                        <img src="${friend_image}" alt="avatar">
                                        <span class="message-data-time">${value.created_at}</span>

                                    </div>
                                    <div class="message other-message "> ${value.text} </div>
                                </li>`;
                        messages_html = messages_html + li_text;
                    } else {

                        var li_text = `  <li class="clearfix ">
                                    <div class="message-data text-right">
                                        <span class="message-data-time">${value.created_at}</span>

                                    </div>
                                    <div class="message my-message float-right"> ${value.text} </div>
                                </li>`;
                        messages_html = messages_html + li_text;

                    }


                });

                var append_chat_html = messages_html;

                $(document).find('.chat .chat-text .chat-history ul').prepend(append_chat_html);






            }




        },
        error: function(error) {
            console.log(error);
        }
    });





}
//----------------------------------------

// var scroll = $(document).scrollTop();
// if (scroll < 1) {
//     // Store eference to first message


//     // Prepend new message here (I'm just cloning...)
//     $('body').prepend(firstMsg.clone());

//     // After adding new message(s), set scroll to position of
//     // what was the first message
//     $(document).scrollTop(firstMsg.offset().top);
// }



$(".chat-text").scroll(function() {
    var height_scroll = $(this).scrollTop();


    if (height_scroll == 0) {
        $(".spinner_").remove();

        var get_next_page = localStorage.getItem('next_page_url');
        var firstMsg = $('.chat-history ul li:first');

        if (get_next_page) {

            var get_api_token = localStorage.getItem('api_token');
            var get_friend_id = localStorage.getItem('friend_id');

            appendSpinnerAnimation();

            console.log('starts fetch latest chat message : url :=> ' + get_next_page);

            fetchChatMessage(get_friend_id, get_api_token, get_next_page);
            $(".spinner_").remove();

            $(".chat-text").scrollTop(firstMsg.offset().top);

            // $('.chat-text').animate({
            //     scrollTop: parseInt(firstMsg.offset().top)
            // }, 1000);


        }

    }
    // console.log(height_scroll);
});


//-------------------------------------------------
// function pagenation(pagination_details){
//     var next_page_url = pagination_details.next_page_url;
//     if(next_page_url != null){

//     }
// }
//-------------------------------------------------

// function appendCahtHistroy() {
//     return `  <p class="text-center text_start_new" style="background:#e8f1f3;font-size:21px;padding: 6px;
//                         "> start new chat</p>`;
// }

//-------------------------------------------------

function changeHeaderCaht(name, image, status = 'online', last_online = null) {
    var image_with_link = `<a class="friend-image" href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                <img src="${image}" alt="avatar">
                            </a>`;
    $(".chat .chat-header .chat-header-image").empty();
    $(".chat .chat-header .chat-header-image").append(image_with_link);
    $(".chat .chat-about .friend_name").text(name);

    if (status == 1) {
        $(".chat .chat-about  .status").html(`<i class="las la-circle online"></i><small>active now</small>`);
    } else {
        $(".chat .chat-about  .status").html(`<i class="las la-circle offline"></i><small class="status">Last seen: ${last_online}</small>`);

    }

}

//-------------------------------------------------

function appendMyNewMessage(mesage) {
    var li_text = `  <li class="clearfix ">
                        <div class="message-data text-right">
                            <span class="message-data-time">now</span>

                        </div>
                        <div class="message my-message float-right"> ${mesage} </div>
                    </li>`;

    $(document).find(".chat-history ul").append(li_text);
    scrolled();
}


//-------------------------------------------------
function scrolled(e) {

    $('.chat-text').animate({
        scrollTop: parseInt($('.chat-history').height())
    }, 1000);
}


//-------------------------------------------------


function appendFormMessage(url, api_token) {
    var append_form = `
                <form class='form_send_message' action="${url}">
                        <input type="hidden" name="api_token" value="${api_token}">
                        <div class="input-group mb-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text button_send">
                                    <i class="lar la-paper-plane"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control text_input" placeholder="Enter text here...">
                        </div>
                    </form>
            `;

    $(".chat-message").html(append_form);
}
//-------------------------------------------------

function appendNewMessageFriend(text_mesage, friend_image, created_at) {
    var li_text = `  <li class="clearfix">
                        <div class="message-data ">
                            <img src="${friend_image}" alt="avatar">
                            <span class="message-data-time">${created_at}</span>

                        </div>
                        <div class="message other-message "> ${text_mesage} </div>
                    </li>`;

    $(document).find(".chat-history ul").append(li_text);
    scrolled();
}


//-------------------------------------------------

$(document).on('keyup', ".text_input", function(e) {
    var input_value = this.value;
    if (input_value.length > 0) {
        $(".button_send").addClass('btn btn-primary');
    } else {
        $(".button_send").removeClass('btn btn-primary');

    }
    //   console.log(this.value);
});

//-------------------------------------------------



$(document).on('submit', '.form_send_message', function(e) {
    e.preventDefault();
});




//-------------------------------------------------


$(document).on('keypress', ".text_input", function(e) {
    var message = this.value;

    if (e.which === 13) {
        e.preventDefault();

        $(".text_input").val('');

        sendMessage(message);

    };

});
//-------------------------------------------------

$(document).on('click', '.button_send', function(e) {
    e.preventDefault();

    var message = $(".text_input").val();
    sendMessage(message);

});

//-------------------------------------------------

function sendMessage(message) {
    var url = $(".form_send_message").attr('action');
    var message = message;
    var api_token = $(".form_send_message").find('input[name="api_token"]').val();


    var data = {
        api_token: api_token,
        message: message
    };

    if (message.length > 0) {
        $.ajax({
            url,
            method: 'post',
            data,
            success: function(data) {

                $(".text_input").val('');
                $(".button_send").removeClass('btn btn-primary');

                console.log(data);
                appendMyNewMessage(message);

            },
            error: function(error) {
                console.log(error);

            }
        });

    }
}

//-------------------------------------------------

//-------------------------------------------------

function appendSpinner() {
    var spinner_html = `<div class="d-flex justify-content-center spinner_"><i class="las la-spinner" style="font-size:32px"></i></div>
    `;

    $(".chat-history").prepend(spinner_html);

}


//-------------------------------------------------

function appendSpinnerAnimation() {
    var spinner_html = `  <div id="circularG" class="spinner_">
                        <div id="circularG_1" class="circularG"></div>
                        <div id="circularG_2" class="circularG"></div>
                        <div id="circularG_3" class="circularG"></div>
                        <div id="circularG_4" class="circularG"></div>
                        <div id="circularG_5" class="circularG"></div>
                        <div id="circularG_6" class="circularG"></div>
                        <div id="circularG_7" class="circularG"></div>
                        <div id="circularG_8" class="circularG"></div>
                    </div>`;
    $(".chat-history").prepend(spinner_html);
}
//-------------------------------------------------
//-------------------------------------------------
//-------------------------------------------------