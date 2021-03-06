<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Config;

class Withdrawal extends Model
{
    use CrudTrait;

    protected $table = 'withdrawals';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['status'];
    // protected $hidden = [];
    // protected $dates = [];

  public function user()
  {
      return $this->belongsTo('App\Models\User');
  }

  /*
  |
  | Show html formatted status in admin panel
  |
  */
  public function getStatusAdmin()
  {
      switch ($this->status) {
          case 0:
              return '<span class="label label-danger">Declined</span>';
          case 1:
              return '<span class="label label-warning">Pending</span>';
          case 2:
              return '<span class="label label-success">Complete</span>';
      }
  }

  /*
  |
  | Get user for backend
  |
  */
  public function getUserAdmin()
  {
      if ($this->user->isOnline()) {
          return '<div class="user-block">
        <img class="img-circle" src="' . $this->user->avatar_square_tiny . '" alt="User Image">
        <span class="username"><a href="' . $this->user->url .'" target="_blank">' . $this->user->name . '</a></span>
        <span class="description"><i class="fa fa-circle text-success"></i> User ID: <strong>' . $this->user->id . '</strong></span>
      </div>';
      } else {
          return '<div class="user-block">
          <img class="img-circle" src="' . $this->user->avatar_square_tiny . '" alt="User Image">
          <span class="username"><a href="' . $this->user->url .'" target="_blank">' . $this->user->name . '</a></span>
          <span class="description inline-block"><i class="fa fa-circle text-danger"></i> User ID: <strong>' . $this->user->id . '</strong></span>
        </div>';
      }
  }

  /*
  |
  | Amount
  |
  */
  public function getAmountAdmin()
  {
      return '<div class="block">
            <strong style="font-size: 18px;">' . number_format($this->fresh()->total,2) . ' '. $this->fresh()->currency .' </strong> <br />
            </strong>
      </div>';
  }

  /*
  |
  | Payment details
  |
  */
  public function getDetailsAdmin()
  {
      return '<div">
        Payment method:  <strong>' . ucfirst($this->payment_method) . '</strong> <br />
        Details:  <strong>' . $this->payment_details . '</strong>
      </div>';
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
