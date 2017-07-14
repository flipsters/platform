<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Digital extends Model
{
    use CrudTrait;

    //protected $table = 'digitals';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','description'];
    // protected $hidden = [];
    // protected $dates = [];

}
