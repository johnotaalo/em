<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisionUpload extends Model
{
    protected $fillable = ["counties", "path", "is_legacy"];
}
