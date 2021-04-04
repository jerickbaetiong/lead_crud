<?php

namespace App;

use App\LeadTags;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['email', 'notes'];

    protected $hidden = ['pivot'];
  
        public function tags()
        {
            return $this->belongsToMany('App\Tags')->select(['tags.id', 'name']);
        }
    
}