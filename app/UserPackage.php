<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    //
    protected $table = 'user_package';
    public $timestamps = false;
    protected $guarded = ['id'];
}
