<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Country extends Model
{
    use CrudTrait;

    protected $table = 'countries';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','code','native'];
    // protected $hidden = [];
    // protected $dates = [];


    /*
    |
    | Get Name with cover and release year for backend
    |
    */
    public function getNameAdmin()
    {
        return '<div class="user-block">
					<img class="img-circle" src="' . 'https://unpkg.com/swapifier@20.0.0/img/flags/' .   $this->code . '.svg' . '" alt="User Image">
					<span class="username">' . $this->name . '</span>
				</div>';
    }
}
