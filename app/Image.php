<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'folder_id', 'image_name', 'slug', 'image_path'
    ];
    
    /**
     * Get the user that owns the image.
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the folder that owns the image.
    */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
