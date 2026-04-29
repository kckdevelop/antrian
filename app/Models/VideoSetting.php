<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSetting extends Model
{
    protected $table = 'video_settings';
    protected $fillable = ['title', 'video_path', 'embed_url', 'is_active'];

    // Akses file video dari storage
    public function getVideoUrlAttribute()
    {
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }
}