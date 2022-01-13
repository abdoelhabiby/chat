@extends('layouts.app')

<style>
    #video {
        width: 350px;
        height: 300px;
        background: #666;
        border: solid 2px black;

    }

</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Camra</div>

                    <div class="card-body">

                        <video autoplay='true' id="video"></video>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')

<script>

    let video = document.querySelector("#video");

  if(navigator.mediaDevices.getUserMedia){
    navigator.mediaDevices.getUserMedia({video:true})
    .then(function(stream){
        video.srcObject = stream;
    })
    .catch(function(error){
        console.log(error);
    })
  }else{
      console.log("getUsersMedia not supoorted");
  }

</script>

@stop
