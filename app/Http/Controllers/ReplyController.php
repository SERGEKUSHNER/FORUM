<?php

namespace App\Http\Controllers;
use App\Thread;
use Illuminate\Http\Request;
use App\Reply;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except'=>'index']);
    }

    public function index($channelId, Thread $thread){
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread, CreatePostRequest $form){

        if ($thread->locked) {
            return response('Thread is locked', 422);
        }

        return $thread->addReply([
            'body'=>request('body'),
            'user_id'=>auth()->id()
        ])->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        request()->validate(['body' => 'required|spamfree']);
        $reply->update(request(['body']));

    }

    public function destroy(Reply $reply)
    {
        if($reply->user_id !=auth()->id()){
            return response([],403);
        }

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();

    }


}
