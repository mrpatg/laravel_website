<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // These values are default, but this is how you would change them
    // Table name 
    protected $table = 'posts';
    // Primary key field
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    // Relationships
    public function user(){
        return $this->belongsTo('App\User');
    }
    

}
