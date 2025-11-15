<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'image_path','genre', 'user_id'
    ];
    public function episodes(){
        return $this->hasMany(Episode::class);
    }
}
