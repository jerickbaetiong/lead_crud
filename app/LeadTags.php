<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadTags extends Model
{
    protected $fillable = ['lead_id', 'tags_id'];

    protected $hidden = ['pivot'];
}
