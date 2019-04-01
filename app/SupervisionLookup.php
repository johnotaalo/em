<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisionLookup extends Model
{
    protected $fillable = [
		'supervision_no',
		'county',
		'period'
    ];
}
