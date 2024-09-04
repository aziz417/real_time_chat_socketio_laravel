<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Socket.IO chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <style>
        /* *******************************
         message-area
        ******************************** */

        .message-area {
            height: 100vh;
            overflow: hidden;
            padding: 30px 0;
            background: #f5f5f5;
        }

        .chat-area {
            position: relative;
            width: 100%;
            background-color: #fff;
            border-radius: 0.3rem;
            height: 90vh;
            overflow: hidden;
            min-height: calc(100% - 1rem);
        }

        .chatlist {
            outline: 0;
            height: 100%;
            overflow: hidden;
            width: 300px;
            float: left;
            padding: 15px;
        }

        .chat-area .modal-content {
            border: none;
            border-radius: 0;
            outline: 0;
            height: 100%;
        }

        .chat-area .modal-dialog-scrollable {
            height: 100% !important;
        }

        .chatbox {
            width: auto;
            overflow: hidden;
            height: 100%;
            border-left: 1px solid #ccc;
        }

        .chatbox .modal-dialog,
        .chatlist .modal-dialog {
            max-width: 100%;
            margin: 0;
        }

        .msg-search {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-area .form-control {
            display: block;
            width: 80%;
            padding: 0.375rem 0.75rem;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            color: #222;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ccc;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .chat-area .form-control:focus {
            outline: 0;
            box-shadow: inherit;
        }

        a.add img {
            height: 36px;
        }

        .chat-area .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            align-items: center;
            justify-content: space-between;
            flex-wrap: inherit;
        }

        .chat-area .nav-tabs .nav-item {
            width: 100%;
        }

        .chat-area .nav-tabs .nav-link {
            width: 100%;
            color: #180660;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.5;
            text-transform: capitalize;
            margin-top: 5px;
            margin-bottom: -1px;
            background: 0 0;
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }

        .chat-area .nav-tabs .nav-item.show .nav-link,
        .chat-area .nav-tabs .nav-link.active {
            color: #222;
            background-color: #fff;
            border-color: transparent transparent #000;
        }

        .chat-area .nav-tabs .nav-link:focus,
        .chat-area .nav-tabs .nav-link:hover {
            border-color: transparent transparent #000;
            isolation: isolate;
        }

        .chat-list h3 {
            color: #222;
            font-size: 16px;
            font-weight: 500;
            line-height: 1.5;
            text-transform: capitalize;
            margin-bottom: 0;
        }

        .chat-list p {
            color: #343434;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            text-transform: capitalize;
            margin-bottom: 0;
        }

        .chat-list a.d-flex {
            margin-bottom: 15px;
            position: relative;
            text-decoration: none;
        }

        .chat-list .active {
            display: block;
            content: '';
            clear: both;
            position: absolute;
            bottom: 3px;
            left: 34px;
            height: 12px;
            width: 12px;
            background: #00DB75;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .msg-head h3 {
            color: #222;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .msg-head p {
            color: #343434;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            text-transform: capitalize;
            margin-bottom: 0;
        }

        .msg-head {
            padding: 15px;
            border-bottom: 1px solid #ccc;
        }

        .moreoption {
            display: flex;
            align-items: center;
            justify-content: end;
        }

        .moreoption .navbar {
            padding: 0;
        }

        .moreoption li .nav-link {
            color: #222;
            font-size: 16px;
        }

        .moreoption .dropdown-toggle::after {
            display: none;
        }

        .moreoption .dropdown-menu[data-bs-popper] {
            top: 100%;
            left: auto;
            right: 0;
            margin-top: 0.125rem;
        }

        .msg-body ul {
            overflow: hidden;
        }

        .msg-body ul li {
            list-style: none;
            margin: 15px 0;
        }

        .msg-body ul li.sender {
            display: block;
            width: 100%;
            position: relative;
        }

        .msg-body ul li.sender:before {
            display: block;
            clear: both;
            content: '';
            position: absolute;
            top: -6px;
            left: -7px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 12px 15px 12px;
            border-color: transparent transparent #f5f5f5 transparent;
            -webkit-transform: rotate(-37deg);
            -ms-transform: rotate(-37deg);
            transform: rotate(-37deg);
        }

        .msg-body ul li.sender p {
            color: #000;
            font-size: 14px;
            line-height: 1.5;
            font-weight: 400;
            padding: 15px;
            background: #f5f5f5;
            display: inline-block;
            border-bottom-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            margin-bottom: 0;
        }

        .msg-body ul li.sender p b {
            display: block;
            color: #180660;
            font-size: 14px;
            line-height: 1.5;
            font-weight: 500;
        }

        .msg-body ul li.repaly {
            display: block;
            width: 100%;
            text-align: right;
            position: relative;
        }

        .msg-body ul li.repaly:before {
            display: block;
            clear: both;
            content: '';
            position: absolute;
            bottom: 15px;
            right: -7px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 12px 15px 12px;
            border-color: transparent transparent #4b7bec transparent;
            -webkit-transform: rotate(37deg);
            -ms-transform: rotate(37deg);
            transform: rotate(37deg);
        }

        .msg-body ul li.repaly p {
            color: #fff;
            font-size: 14px;
            line-height: 1.5;
            font-weight: 400;
            padding: 15px;
            background: #4b7bec;
            display: inline-block;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-left-radius: 10px;
            margin-bottom: 0;
        }

        .msg-body ul li.repaly p b {
            display: block;
            color: #061061;
            font-size: 14px;
            line-height: 1.5;
            font-weight: 500;
        }

        .msg-body ul li.repaly:after {
            display: block;
            content: '';
            clear: both;
        }

        .time {
            display: block;
            color: #000;
            font-size: 12px;
            line-height: 1.5;
            font-weight: 400;
        }

        li.repaly .time {
            margin-right: 20px;
        }

        .divider {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .msg-body h6 {
            text-align: center;
            font-weight: normal;
            font-size: 14px;
            line-height: 1.5;
            color: #222;
            background: #fff;
            display: inline-block;
            padding: 0 5px;
            margin-bottom: 0;
        }

        .divider:after {
            display: block;
            content: '';
            clear: both;
            position: absolute;
            top: 12px;
            left: 0;
            border-top: 1px solid #EBEBEB;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .send-box {
            padding: 15px;
            border-top: 1px solid #ccc;
        }

        .send-box form {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .send-box .form-control {
            display: block;
            width: 85%;
            padding: 0.375rem 0.75rem;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            color: #222;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ccc;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .send-box button {
            border: none;
            background: #3867d6;
            padding: 0.375rem 5px;
            color: #fff;
            border-radius: 0.25rem;
            font-size: 14px;
            font-weight: 400;
            width: 24%;
            margin-left: 1%;
        }

        .send-box button i {
            margin-right: 5px;
        }

        .send-btns .button-wrapper {
            position: relative;
            width: 125px;
            height: auto;
            text-align: left;
            margin: 0 auto;
            display: block;
            background: #F6F7FA;
            border-radius: 3px;
            padding: 5px 15px;
            float: left;
            margin-right: 5px;
            margin-bottom: 5px;
            overflow: hidden;
        }

        .send-btns .button-wrapper span.label {
            position: relative;
            z-index: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            width: 100%;
            cursor: pointer;
            color: #343945;
            font-weight: 400;
            text-transform: capitalize;
            font-size: 13px;
        }

        #upload {
            display: inline-block;
            position: absolute;
            z-index: 1;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .send-btns .attach .form-control {
            display: inline-block;
            width: 120px;
            height: auto;
            padding: 5px 8px;
            font-size: 13px;
            font-weight: 400;
            line-height: 1.5;
            color: #343945;
            background-color: #F6F7FA;
            background-clip: padding-box;
            border: 1px solid #F6F7FA;
            border-radius: 3px;
            margin-bottom: 5px;
        }

        .send-btns .button-wrapper span.label img {
            margin-right: 5px;
        }

        .button-wrapper {
            position: relative;
            width: 100px;
            height: 100px;
            text-align: center;
            margin: 0 auto;
        }

        button:focus {
            outline: 0;
        }

        .add-apoint {
            display: inline-block;
            margin-left: 5px;
        }

        .add-apoint a {
            text-decoration: none;
            background: #F6F7FA;
            border-radius: 8px;
            padding: 8px 8px;
            font-size: 13px;
            font-weight: 400;
            line-height: 1.2;
            color: #343945;
        }

        .selectedUserBg {
            background: #ddd;

        }

        .add-apoint a svg {
            margin-right: 5px;
        }

        .chat-icon {
            display: none;
        }

        .closess i {
            display: none;
        }

        .profileImageReciever {
            background-color: #4b7bec;
            color: #fff;
            width: 54px;
            height: 55px;
            border: 2px solid #fff;
            border-radius: 100%;
            font-size: 23px;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        @media (max-width: 767px) {
            .chat-icon {
                display: block;
                margin-right: 5px;
            }

            .chatlist {
                width: 100%;
            }

            .chatbox {
                width: 100%;
                position: absolute;
                left: 1000px;
                right: 0;
                background: #fff;
                transition: all 0.5s ease;
                border-left: none;
            }

            .showbox {
                left: 0 !important;
                transition: all 0.5s ease;
            }

            .msg-head h3 {
                font-size: 14px;
            }

            .msg-head p {
                font-size: 12px;
            }

            .msg-head .flex-shrink-0 img {
                height: 30px;
            }

            .send-box button {
                width: 28%;
            }

            .send-box .form-control {
                width: 70%;
            }

            .chat-list h3 {
                font-size: 14px;
            }

            .chat-list p {
                font-size: 12px;
            }

            .msg-body ul li.sender p {
                font-size: 13px;
                padding: 8px;
                border-bottom-left-radius: 6px;
                border-top-right-radius: 6px;
                border-bottom-right-radius: 6px;
            }

            .msg-body ul li.repaly p {
                font-size: 13px;
                padding: 8px;
                border-top-left-radius: 6px;
                border-top-right-radius: 6px;
                border-bottom-left-radius: 6px;
            }
        }
    </style>
</head>
<!-- char-area -->
@include('layouts.navbar')
<section class="message-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <!-- chatlist -->
                    <div class="chatlist">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content">


                                <div class="modal-body">
                                    <!-- chat-list -->
                                    <div class="chat-lists">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="Open" role="tabpanel"
                                                aria-labelledby="Open-tab">
                                                <!-- chat-list -->
                                                <div id="userList" class="chat-list">
                                                    @forelse ($users as $user)
                                                        <a href="{{ route('chat', $user->id) }}"
                                                            onclick="userSelect({{ $user->id }})"
                                                            class="d-flex align-items-center  {{ $firstUser && $firstUser->id == $user->id ? 'selectedUserBg' : '' }}">
                                                            <div class="flex-shrink-0 p-1 profileImageReciever">
                                                                {{ shortName($user->name) }}

                                                                <span class="active-user-{{ $user->id }}"></span>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h3>{{ $user->name }}</h3>
                                                            </div>
                                                        </a>
                                                    @empty
                                                        <p>Not Found Users</p>
                                                    @endforelse
                                                </div>
                                                <!-- chat-list -->
                                            </div>

                                        </div>

                                    </div>
                                    <!-- chat-list -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chatlist -->

                    @if ($firstUser)
                        <!-- chatbox -->
                        <div class="chatbox">
                            <div class="modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="msg-head">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="d-flex align-items-center">
                                                    <span class="chat-icon"><img class="img-fluid"
                                                            src="https://mehedihtml.com/chatbox/assets/img/arroleftt.svg"
                                                            alt="image title"></span>
                                                    <div class="flex-shrink-0 profileImageReciever">
                                                        {{ shortName($firstUser->name) }}
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 class="selectedUser">{{ $firstUser->name }}</h3>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div class="msg-body">
                                            <ul id="messages">
                                                @forelse ($messages as $message)
                                                    @php
                                                        // Set the timezone to Dhaka
                                                        $dhakaTime = $message->created_at->timezone('Asia/Dhaka');
                                                    @endphp

                                                    @if ($message->sender_id !== auth()->user()->id)
                                                        <li class="sender">
                                                            <p>{{ $message->message }}</p>
                                                            <span
                                                                class="time">{{ $dhakaTime->format('h:i a') }}</span>
                                                        </li>
                                                    @else
                                                        <li class="repaly">
                                                            <p>{{ $message->message }}</p>
                                                            <span
                                                                class="time">{{ $dhakaTime->format('h:i a') }}</span>
                                                        </li>
                                                    @endif
                                                @empty
                                                    <li class="flex justify-center items-center">
                                                        <p class="text-center">You Can Start Conversation!</p>
                                                    </li>
                                                @endforelse

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="send-box">
                                        <form action="" id="form">
                                            <input type="text" id="input" class="form-control"
                                                aria-label="message…" placeholder="Write message…">

                                            <button><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                Send</button>
                                        </form>
                                    </div>
                                </div>


                            </div>
                        </div>
                </div>
                <!-- chatbox -->
                @endif
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Load the Socket.IO client library -->
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function() {
        // Call scrollToBottom when needed, e.g., after appending a new message
        $('.modal-body').scrollTop($('.modal-body')[0].scrollHeight + 400);
    });

    // Function to format the time
    function getCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'pm' : 'am';

        hours = hours % 12;
        hours = hours ? hours : 12; // The hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;

        return `${hours}:${minutes} ${ampm}`;
    }

    // Connect to the Socket.IO server
    const socket = io('http://localhost:3000');

    const form = document.getElementById("form");
    const input = document.getElementById("input");

    const first_user = {{ $firstUser ? $firstUser->id : 1 }};

    socket.on('connect', function() {
        socket.emit("user_connected", {{ auth()->user()->id }});
    })

    socket.emit("registerUser", {{ auth()->user()->id }});


    function userSelect(user_id) {
        window.selectedUserId = user_id;
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        let url = `{{ route('messages.send') }}`
        const data = {
            'receiver_id': window.selectedUserId ?? first_user,
            'message': input.value,
        }

        $.post('/messages', data)
            .done(function(response) {
                console.log('Success:', response);
            })
            .fail(function(xhr, status, error) {
                console.log('Error:', error);
            });

        const item = document.createElement('li');
        item.classList.add('repaly');
        const time = getCurrentTime();
        item.innerHTML = `<p>${input.value}</p><span class="time">${time}</span>`;
        document.getElementById("messages").appendChild(item);



        if (input.value) {
            const message = input.value;
            socket.emit("sendPrivateMessage", {
                recipientId: window.selectedUserId ?? first_user,
                message
            });

            const typeMessage = null;
            socket.emit("sendPrivateMessageTyping", {
                recipientId: window.selectedUserId ?? first_user,
                typeMessage
            });
            input.value = "";
        }

        $('.modal-body').scrollTop($('.modal-body')[0].scrollHeight + 400);

    });

    // Receive a message from the server and display it
    socket.on("receiveMessage", (msg) => {
        const time = getCurrentTime();
        const item = document.createElement('li');
        item.classList.add('sender');
        item.innerHTML = `<p>${msg}</p><span class="time">${time}</span>`;
        document.getElementById("messages").appendChild(item);
        $('.modal-body').scrollTop($('.modal-body')[0].scrollHeight + 400);
    });


    $("#input").on('input', function() {
        const typeMessage = "Typing...";
        socket.emit("sendPrivateMessageTyping", {
            recipientId: window.selectedUserId ?? first_user,
            typeMessage
        });
    })

    // Receive a message from the server and display it
    socket.on("receiveMessageTyping", (typeMessage) => {
        if (!$("#typing").length) {
            $("#messages").append(`<li id="typing"><p>${typeMessage}</p></li>`);
            $('.modal-body').scrollTop($('.modal-body')[0].scrollHeight + 400);

        }

        if (!typeMessage) {
            $("#typing").remove();
        }
    });

    // //active user 
    socket.on('activeUsers', (activeUsers) => {
        console.log('Active Users:', activeUsers);
        $('#userList .active').removeClass('active');

        $.each(activeUsers, function(key, val) {          
            if (val !== null && val !== 0) {
                let $userIcon = $(".active-user-" + val);
                $userIcon.addClass('active');
            }
        });
    });
</script>

</body>

</html>
