<?php

namespace App\Http\Controllers;

use App\Http\Requests\PodcastRequest;
use App\Models\Podcast;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index(){
        $podcast=Podcast::all();
        return Response()->json(['podcasts'=>$podcast]);
    }
    public function store(PodcastRequest $request){
        $user=$request->user();
        $podcast=Podcast::create($request->validated());
        return Response()->json(['message'=>'podcast created successfully', 'podcasts'=>$podcast]);
    }

    public function update(PodcastRequest $request){
        $id=$request->route('id');
        $podcast=Podcast::findOrFail($id);
        $podcast->update($request->validated());

        return Response()->json(['message'=>'podcast updated successfully', 'podcasts'=>$podcast]);

    }

    public function destroy($id){
    $podcast=podcast::findOrFail($id);
    $podcast->delete();
    return Response()->json(['message'=> 'podcast deleted successfully', 'podcast'=>$podcast]);

    }

}
