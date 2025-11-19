<?php

namespace App\Http\Controllers;

use App\Http\Requests\EpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Episode;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
     public function index(){
        try {
            $episodes = Episode::all();
            
            return response()->json([
                'message' => 'Episodes retrieved successfully',
                'episodes' => $episodes,
                'count' => $episodes->count()
            ], 200);
            
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'Failed to retrieve episodes from database.',
                'details' => 'Please check database connection and try again.'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Retrieval Failed',
                'message' => 'An unexpected error occurred while retrieving episodes.',
                'details' => 'Please try again later or contact support.'
            ], 500);
        }
    }
    public function store(EpisodeRequest $request){
        try {
            $episodeData = $request->validated();
            
            // Handle audio file upload to Cloudinary if provided
            if ($request->hasFile('audio_file')) {
                $uploadedAudio = Cloudinary::upload(
                    $request->file('audio_file')->getRealPath(),
                    [
                        'folder' => 'episodes/audio',
                        'resource_type' => 'video' // For audio files
                    ]
                );
                
                $episodeData['audio_path'] = $uploadedAudio->getSecurePath();
            }
            
            $episode = Episode::create($episodeData);
            
            return response()->json([
                'message' => 'Episode created successfully',
                'episode' => $episode
            ], 201);
            
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'error' => 'Database Constraint Error',
                    'message' => 'Invalid podcast ID or database constraint violation.',
                    'details' => 'Please check that the podcast exists and try again.'
                ], 422);
            };
    };
}

    public function update(UpdateEpisodeRequest $request, Episode $episode){
        try {
            if (!$episode) {
                return response()->json([
                    'error' => 'Episode Not Found',
                    'message' => 'The episode you are trying to update does not exist.',
                    'details' => 'Please check the episode ID and try again.'
                ], 404);
            }
            
            $episodeData = $request->validated();
            
            // Handle audio file upload to Cloudinary if provided
            if ($request->hasFile('audio_file')) {
                $uploadedAudio = Cloudinary::upload(
                    $request->file('audio_file')->getRealPath(),
                    [
                        'folder' => 'episodes/audio',
                        'resource_type' => 'video' // For audio files
                    ]
                );
                
                $episodeData['audio_path'] = $uploadedAudio->getSecurePath();
            }
            
            $episode->update($episodeData);
            $episode->refresh();
            
            return response()->json([
                'message' => 'Episode updated successfully',
                'episode' => $episode
            ], 200);
            
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'error' => 'Database Constraint Error',
                    'message' => 'Invalid podcast ID or database constraint violation.',
                    'details' => 'Please check that the podcast exists and try again.'
                ], 422);
            }
            
            return response()->json([
                'error' => 'Episode Update Failed',
                'message' => 'An unexpected error occurred while updating the episode.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    //   public function update(EpisodeRequest $request, string $id){
    //     $id=$request->route('id');
    //     $episode=Episode::findOrFail($id);
    //     $episode->update($request->validated());
    //     return Response()->json(['message'=>'episode updated successfully', 'episodes'=>$episode]);
    // }

    public function destroy($id){
        try {
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'error' => 'Invalid Parameter',
                    'message' => 'A valid episode ID is required.',
                    'details' => 'Please provide a numeric episode ID.'
                ], 400);
            }
            
            $episode = Episode::findOrFail($id);
            
            $episodeData = $episode->toArray();
            
            $episode->delete();
            
            return response()->json([
                'message' => 'Episode deleted successfully',
                'deleted_episode' => $episodeData
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Episode Not Found',
                'message' => 'The episode you are trying to delete does not exist.',
                'details' => 'Please check the episode ID and try again.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'Failed to delete episode due to database error.',
                'details' => 'Please try again later.'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Episode Deletion Failed',
                'message' => 'An unexpected error occurred while deleting the episode.',
                'details' => 'Please try again later or contact support.'
            ], 500);
        }
    }
    // public function destroy(Episode $episode){
    // $episode->delete();
    // return Response()->json(['message'=> 'episode deleted successfully', 'episodes'=>$episode]);

    // }

}

