<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Genre extends Model
{
    use CrudTrait;

    protected $table = 'genres';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name'];
    // protected $hidden = [];
    // protected $dates = [];

}
