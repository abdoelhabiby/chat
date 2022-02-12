@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>



                    <div class="card-body m-auto">

                        <div>



                            <a id="download_image" class="btn btn-danger"><i class="las la-download "
                                    style="font-size: 22px"></i></a>
                        </div>


                        <button class="btn btn-primary rounded-circle"><i class="las la-microphone "
                                style="font-size: 22px"></i></button>

                        <div>

                            {{-- <input type="file" accept="audio/*" capture id="recorder"> --}}

                            <audio id="player" controls></audio>



                            <a id="download">Download</a>
                            <button id="start">Start</button>
                            <button id="pause" disabled>Pause</button>
                            <button id="resume" disabled>Resume</button>
                            <button id="stop" disabled>Stop</button>
                            <button id="send" disabled>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')



    <script>
        const downloadLink = document.getElementById('download');
        const startButton = document.getElementById('start');
        const pauseButton = document.getElementById('pause');
        const resumeButton = document.getElementById('resume');
        const stopButton = document.getElementById('stop');
        const sendButton = document.getElementById('send');
        const player = document.getElementById('player');


        // navigator.permissions.query({
        //     name: 'microphone'
        // }).then(function(result) {
        //     if (result.state == 'granted') {
        //         console.log(" is granted");

        //     } else if (result.state == 'prompt') {
        //         console.log(" is prompt");

        //     } else if (result.state == 'denied') {
        //         console.log(" is denied");

        //     }

        //     result.onchange = function() {

        //     };

        // });


        var AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext = new AudioContext;

        const handleSuccess = function(stream) {
            const options = {
                mimeType: 'audio/webm'
            };
            const recordedChunks = [];
            const mediaRecorder = new MediaRecorder(stream, options);

            mediaRecorder.addEventListener('dataavailable', function(e) {
                if (e.data.size > 0) recordedChunks.push(e.data);



            });



            function handelRecordAndSend() {

                const the_url = URL.createObjectURL(new Blob(recordedChunks, {
                    type: 'audio/x-wav'
                }));
                downloadLink.href = the_url;
                downloadLink.download = 'kartal.wav';

                // player.src = the_url; // this work

                // player.srcObject = stream; // and this work

                // if (window.URL) {
                //     player.srcObject = stream;
                //     console.log(stream);
                // } else {
                //     player.src = stream;
                // }




                const blob = new Blob(recordedChunks, {
                    type: 'audio/x-wav'
                });

                var fd = new FormData();

                fd.append("_token", "{{ csrf_token() }}");
                fd.append("file", blob, 'y3am.wav');

                // console.log(blob.text());








                $(function() {

                    $.ajax({
                        url: "test_audio_file",
                        method: "post",
                        processData: false,
                        contentType: false,
                        data: fd,

                        success: function(su) {

                            console.log(mediaRecorder.state);
                            console.log(su);
                            const testo = document.getElementById('player');
                            testo.src =  su.url_record;

                            setTimeout(() => {
                                console.log('get duration afte request svae record is : ' + document.getElementById('player').duration);
                            }, 5000);

                        },
                        error: function(er) {
                            console.log(er);
                        }
                    });

                });


            }

            // mediaRecorder.addEventListener('stop', function() {




            // });

            stopButton.addEventListener('click', function() {

                if (mediaRecorder.state == 'recording') {
                    mediaRecorder.stop();
                }

                sendButton.disabled = false;
                stopButton.disabled = true;
                resumeButton.disabled = true;
                pauseButton.disabled = true;

                startButton.style.background = "#d0d6db";
                startButton.style.color = "black";
                console.log(mediaRecorder.state);

            });

            pauseButton.addEventListener('click', function() {
                if (mediaRecorder.state == 'recording') {
                    mediaRecorder.pause();
                }


                sendButton.disabled = false;
                startButton.disabled = true;
                resumeButton.disabled = false;

                startButton.style.background = "#d0d6db";
                startButton.style.color = "black";
                console.log(mediaRecorder.state);

            });

            startButton.addEventListener('click', function() {



                if (mediaRecorder.state == 'recording') {
                    mediaRecorder.stop();
                }


                mediaRecorder.start();
                startButton.style.background = "red";
                startButton.style.color = "black";
                stopButton.disabled = false;
                pauseButton.disabled = false;
                // sendButton.disabled = false;

                // console.log(mediaRecorder.state);

            });

            resumeButton.addEventListener('click', function() {

                if (mediaRecorder.state == 'paused') {
                    mediaRecorder.resume();
                }

                startButton.style.background = "red";
                startButton.style.color = "black";
                stopButton.disabled = false;
                resumeButton.disabled = true;
                pauseButton.disabled = false;

                console.log(mediaRecorder.state);

            });

            sendButton.addEventListener('click', function() {



                if (MediaRecorder.state == "recording") {
                    mediaRecorder.stop();
                    console.log('from here');
                }

                //  mediaRecorder.stop();

                handelRecordAndSend();
                if (MediaRecorder.state == "recording") {
                    mediaRecorder.stop();
                }

                console.log("latest : " + mediaRecorder.state);

                stopButton.disabled = true;
                pauseButton.disabled = true;
                sendButton.disabled = true;
                startButton.disabled = false;





            });


        };



        navigator.mediaDevices.getUserMedia({
                audio: true,
                video: false
            })
            .then(handleSuccess);
    </script>







    {{-- <script>
    const handleSuccess = function(stream) {
      const context = new AudioContext();
      const source = context.createMediaStreamSource(stream);
      const processor = context.createScriptProcessor(1024, 1, 1);

      source.connect(processor);
      processor.connect(context.destination);

      processor.onaudioprocess = function(e) {
        // Do something with the data, e.g. convert it to WAV
        console.log(e.inputBuffer);
      };
    };

    navigator.mediaDevices.getUserMedia({ audio: true, video: false })
        .then(handleSuccess);
  </script> --}}

    {{-- connect to microphone --}}
    {{-- <script>
        const recorder = document.getElementById('recorder');
        const player = document.getElementById('player');

        const handleSuccess = function(stream) {
            if (window.URL) {
                player.srcObject = stream;
                console.log(stream);
            } else {
                player.src = stream;
            }
        };

        navigator.mediaDevices.getUserMedia({
                audio: true,
                video: false
            })
            .then(handleSuccess);
    </script> --}}
@endsection
