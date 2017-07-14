<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metacritic extends Model
{

    protected $table = 'games_metacritic';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];


    /*
    |
    | Get CSS Class for metascore
    |
    */
    public function getScoreClassAttribute()
    {
        if ($this->score >= 75) {
            return 'great';
        }

        if ($this->score >= 50) {
            return 'average';
        }

        if ($this->score >= 0) {
            return 'poor';
        }
    }
}
