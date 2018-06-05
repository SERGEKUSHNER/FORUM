<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);
    }

    /**
     * Unlock the given thread.
     *
     * @param \App\Thread $thread
     */
    public function destroy(Thread $thread)
    {
        $thread->update(['locked' => false]);
    }
}
