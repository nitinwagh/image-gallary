<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'parent_id', 'folder_name',
    ];
    
    /**
     * Get the user that owns the folder.
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the images for the folder.
    */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
