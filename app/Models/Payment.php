<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Config;

class Payment extends Model
{
    use CrudTrait;

    protected $table = 'payments';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

  /**
   * Get all of the owning item models.
   */
  public function item()
  {
      return $this->morphTo();
  }

  public function user()
  {
      return $this->belongsTo('App\Models\User');
  }

  public function offer()
  {
      return $this->belongsTo('App\Models\Offer','item_id','id')->withTrashed();
  }

  public function transactions()
  {
      return $this->hasMany('App\Models\Transaction');
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
              return '<span class="label label-warning">Refunded</span>';
          case 1:
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
  | Get Name with cover and release year for backend
  |
  */
  public function getItemAdmin()
  {
      return '<div class="user-block">
          <img class="img-circle" src="' . $this->offer->listing->game->image_square_tiny . '" alt="User Image">
          <span class="username"><a href="' . $this->offer->url .'" target="_blank">' . $this->offer->listing->game->name . '</a></span>
          <span class="description"><span class="label" style="background-color: '. $this->offer->listing->game->platform->color . '; margin-right: 10px;">' . $this->offer->listing->game->platform->name .'</span><i class="fa fa-calendar"></i> ' . $this->offer->listing->game->release_date->format('Y') . '</span>
      </div>';
  }

  /*
  |
  | Amount + transaction fees for backend
  |
  */
  public function getAmountAdmin()
  {
      return '<div class="block">
            <strong class="' . ($this->fresh()->status == 1 ? 'text-success' : 'text-warning') . '" style="font-size: 18px;">' . number_format($this->fresh()->total,2) . ' '. $this->fresh()->currency .' </strong> <br />
            Transaction fees: <strong class="' . ($this->fresh()->status == 1 ? 'text-danger' : '') . '">' . number_format($this->fresh()->transaction_fee,2) . ' '. $this->fresh()->currency .'</strong>
      </div>';
  }

  /*
  |
  | Payment Details for backend
  |
  */
  public function getPaymentInfoAdmin()
  {
      return '<div class="block">
            Transaction ID: <strong>' . $this->transaction_id . ' </strong> <br />
            <i class="fa fa-paypal"></i> PayPal
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
