@extends('layouts.app')

@section('content')
    <div class="container">


        @foreach ($posts as $post)

            <div class="card mb-3" style="">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }} ({{$post->user->name}})</h5>
                    <p class="card-text">{{ $post->post }}</p>
                    <a href="{{ route('posts.show', $post->id) }}" class="card-link">show</a>
                </div>
            </div>


        @endforeach


        <div class="d-flex justify-content-center mt-5">

            {{ $posts->appends(request()->query())->links() }}

        </div>


    </div>

@endsection
