<?php

namespace App\Http\Controllers;

use App\Http\Requests\PodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use App\Models\Podcast;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index(){
        try {
            $podcasts = Podcast::all();
            
            return response()->json([
                'message' => 'Podcasts retrieved successfully',
                'podcasts' => $podcasts,
                'count' => $podcasts->count()
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'Failed to retrieve podcasts from database.',
                'details' => 'Please check database connection and try again.'
            ], 500);
            
        }
    }
    public function store(PodcastRequest $request){
        try {
            $user = $request->user();
            $infos=$request->validated();
            
            if (!$user) {
                return response()->json([
                    'error' => 'Authentication Required',
                    'message' => 'You must be logged in to create a podcast.',
                    'details' => 'Please login and try again.'
                ], 401);
            }
            if ($request->hasFile('image_path')) {
                $uploadedImage = Cloudinary::upload(
                    $request->file('image_path')->getRealPath()
                );

                $infos['image_path'] = $uploadedImage->getSecurePath();
            }
            
            $podcast = Podcast::create($infos);
            
            return response()->json([
                'message' => 'Podcast created successfully',
                'podcast' => $podcast
            ], 201);
            
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'error' => 'Database Constraint Error',
                    'message' => 'Invalid user ID or database constraint violation.',
                    'details' => 'Please check that the user exists and try again.'
                ], 422);
            }
        }
    }

   public function update(UpdatePodcastRequest $request)
{
    try {
        $id = $request->route('id');
        
        if (!$id) {
            return response()->json([
                'error' => 'Missing Parameter',
                'message' => 'Podcast ID is required.'
            ], 400);
        }
        
        $podcast = Podcast::findOrFail($id);
        
        $data = $request->validated();

        if ($request->hasFile('image')) {
            
            $uploadedImage = Cloudinary::upload(
                $request->file('image')->getRealPath()
            );
            $data['image_path'] = $uploadedImage->getSecurePath();

            unset($data['image']);
        }
        $podcast->update($data);

        $podcast->refresh();
        
        return response()->json([
            'message' => 'Podcast updated successfully',
            'podcast' => $podcast
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Update Failed',
            'message' => 'The podcast could not be updated.',
        ], 500);
    } 
}

    public function destroy($id){
        try {
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'error' => 'Invalid Parameter',
                    'message' => 'A valid podcast ID is required.',
                    'details' => 'Please provide a numeric podcast ID.'
                ], 400);
            }
            
            $podcast = Podcast::findOrFail($id);
            
            $podcastData = $podcast->toArray();
            
            $podcast->delete();
            
            return response()->json([
                'message' => 'Podcast deleted successfully',
                'deleted_podcast' => $podcastData
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Podcast Not Found',
                'message' => 'The podcast you are trying to delete does not exist.',
                'details' => 'Please check the podcast ID and try again.'
            ], 404);
            
    };

    }
}
