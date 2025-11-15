<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    
    protected $fillable = [
    'title', 'description', 'audio_path', 'podcast_id',

    ];


    public function podcasts(){
        return $this->belongsTo(Podcast::class);
    }
}
