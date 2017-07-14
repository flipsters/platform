<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Config;

class User_Rating extends Model
{
    use CrudTrait;

    protected $table = 'user_ratings';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function user_from()
    {
        return $this->belongsTo('App\Models\User', 'user_id_from', 'id');
    }

    public function user_to()
    {
        return $this->belongsTo('App\Models\User', 'user_id_to', 'id');
    }

    /*
    |
    | Get user for backend
    |
    */
    public function getUserAdmin()
    {
        if ($this->user_from->isOnline()) {
            return '<div class="user-block">
					<img class="img-circle" src="' . $this->user_from->avatar_square_tiny . '" alt="User Image">
					<span class="username"><a href="' . $this->user_from->url .'" target="_blank">' . $this->user_from->name . '</a></span>
					<span class="description"><i class="fa fa-circle text-success"></i> Online</span>
				</div>';
        } else {
            return '<div class="user-block">
						<img class="img-circle" src="' . $this->user_from->avatar_square_tiny . '" alt="User Image">
						<span class="username"><a href="' . $this->user_from->url .'" target="_blank">' . $this->user_from->name . '</a></span>
						<span class="description"><i class="fa fa-circle text-danger"></i> Offline</span>
					</div>';
        }
    }

    /*
    |
    | Get user for backend
    |
    */
    public function getUserToAdmin()
    {
        if ($this->user_to->isOnline()) {
            return '<div class="user-block">
					<img class="img-circle" src="' . $this->user_to->avatar_square_tiny . '" alt="User Image">
					<span class="username"><a href="' . $this->user_to->url .'" target="_blank">' . $this->user_to->name . '</a></span>
					<span class="description"><i class="fa fa-circle text-success"></i> Online</span>
				</div>';
        } else {
            return '<div class="user-block">
						<img class="img-circle" src="' . $this->user_to->avatar_square_tiny . '" alt="User Image">
						<span class="username"><a href="' . $this->user_to->url .'" target="_blank">' . $this->user_to->name . '</a></span>
						<span class="description"><i class="fa fa-circle text-danger"></i> Offline</span>
					</div>';
        }
    }

    /*
    |
    | Show rating in backend
    |
    */
    public function getRatingAdmin()
    {
        switch ($this->rating) {
            case 0:
                return '<h3 style="margin: 0px !important;"><span class="label label-danger"><i class="fa fa-thumbs-down"></i></span></h3>';
            case 1:
                return '<h3 style="margin: 0px !important;"><span class="label label-default"><i class="fa fa-minus"></i></span></h3>';
            case 2:
                return '<h3 style="margin: 0px !important;"><span class="label label-success"><i class="fa fa-thumbs-up"></i></span></h3>';
        }
    }

    /*
    |
    | Get formatted creation date for backend
    |
    */
    public function getDateAdmin()
    {
        return '<strong>' . $this->created_at->format(Config::get('settings.date_format')) . '</strong><br>' . $this->created_at->format(Config::get('settings.time_format'));
    }
}
