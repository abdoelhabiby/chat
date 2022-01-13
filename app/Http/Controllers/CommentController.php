<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use App\Events\NewCommentEvent;
use App\Notifications\PostNewCommentNotification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post,Request $request)
    {
        $data = [
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comment' => $request->comment
        ];

       $comment =  Comment::create($data);
        $post_user = $comment->post->user;


       $content = [
           'comment' => $comment,
           'user' => $comment->user
       ];

       broadcast(new NewCommentEvent($post_user,$comment))->toOthers();
        // event(new NewCommentEvent($post_user,$comment));

       return response($content,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id,$comment_id)
    {
        $comment = Comment::where('post_id',$post_id)->where('user_id',auth()->user()->id)->findOrFail($comment_id);

        $comment->delete();

        return response('success delete',200);

    }
}
