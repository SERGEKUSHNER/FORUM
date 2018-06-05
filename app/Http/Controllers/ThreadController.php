<?php

namespace App\Http\Controllers;
use App\Channel;
use App\Thread;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Trending;
class ThreadController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth')->only('store');
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     * @param  Channel      $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()){
            return $threads;
        }

        //$trending->get();

        return view('threads.index', [
            'threads' => $threads,
            'trending' =>$trending->get()
        ]);

    }


    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

//        $spam->detect(request('body'));

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),

        ]);

        if(request()->wantsJson()){
            return response($thread,201);
        }

        return redirect($thread->path())
                ->with('flash','Your thread has been published');
    }


    public function show($channel, Thread $thread, Trending $trending)
    {

        if (auth()->check()) {
            auth()->user()->read($thread);
        }

    $trending->push($thread);
    $thread->increment('visits');
    return view('threads.show', compact('thread'));

    }


    /**
     * Update the given thread.
     *
     * @param string $channel
     * @param Thread $thread
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]));

        return $thread;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this ->authorize('update',$thread );


        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    public function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        //dd($threads->toSql());
        return $threads->paginate(20);

    }



}

