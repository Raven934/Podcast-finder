<?php

namespace App\Http\Controllers;

use App\Http\Requests\EpisodeRequest;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
     public function index(){
        $episose=Episode::all();
        return Response()->json(['episodes'=>$episose]);
    }
    public function store(EpisodeRequest $request){
        $episode=Episode::create($request->validated());
        return Response()->json(['message'=>'episode created successfully', 'episodes'=>$episode]);
    }

    public function update(EpisodeRequest $request, Episode $episode){
        $episode->update($request->validated());

        return Response()->json(['message'=>'episode updated successfully', 'episodes'=>$episode]);
        
    }
    //   public function update(EpisodeRequest $request, string $id){
    //     $id=$request->route('id');
    //     $episode=Episode::findOrFail($id);
    //     $episode->update($request->validated());
    //     return Response()->json(['message'=>'episode updated successfully', 'episodes'=>$episode]);
    // }

    public function destroy($id){
    $episode=Episode::findOrFail($id);
    $episode->delete();
    return Response()->json(['message'=> 'episode deleted successfully', 'episodes'=>$episode]);

    }
    // public function destroy(Episode $episode){
    // $episode->delete();
    // return Response()->json(['message'=> 'episode deleted successfully', 'episodes'=>$episode]);

    // }

}
