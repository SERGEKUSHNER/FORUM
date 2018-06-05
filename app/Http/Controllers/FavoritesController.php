<?php

namespace App\Http\Controllers;
use App\Reply;
use Illuminate\Http\Request;
use App\Favorite;

class FavoritesController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply){

        $reply->favorite();
        return back();
    //$reply->favorites()->create(['user_id'=>auth()->id()]);
       /* Favorite::create([
           'user_id'=>auth()->id(),
           'favorited_id' => $reply->id,
           'favorited_type' => get_class($reply)
        ]);
*/
    }
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
