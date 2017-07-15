<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\Auth\UserNeedsPasswordReset;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cache;

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait;
    use HasRoles;
    use Messagable;
    use SoftDeletes;

    protected $dates = ['last_activity_at','created_at','deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status','confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserNeedsPasswordReset($token));
    }

    public function listings()
    {
        return $this->hasMany('App\Models\Listing')->orderBy('last_offer_at', 'desc');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer')->orderBy('created_at', 'desc');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\User_Rating', 'user_id_to')->where('active','1')->orderBy('created_at', 'desc');
    }

    public function location()
    {
        return $this->hasOne('App\Models\User_Location');
    }

    public function providers()
    {
        return $this->hasMany('App\Models\SocialLogin');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    /*
    |
    | Get Square Avatar
    |
    */
    public function getAvatarSquareAttribute()
    {
        if (!is_null($this->avatar)) {
            return 'https://i1.wp.com/friendstub.com/images/avatar_square/' . $this->avatar;
        } else {
            return "https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?size=80&default=retro";
        }
    }

    /*
    |
    | Get Square (Tiny) Avatar
    |
    */
    public function getAvatarSquareTinyAttribute()
    {
        if (!is_null($this->avatar)) {
            return 'https://i1.wp.com/friendstub.com/images/avatar_square_tiny/' . $this->avatar;
        } else {
            return "https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?size=50&default=retro";
        }
    }

    /*
    |
    | Get positive ratings in percent
    |
    */
    public function getPositivePercentRatingsAttribute()
    {
        if ($this->ratings->count() > 0) {
            return round(($this->ratings->sum('rating') / ($this->ratings->count()*2)) * 100);
        } else {
            return null;
        }
    }

    /*
    |
    | Get count of positive ratings
    |
    */
    public function getPositiveRatingsAttribute()
    {
        return $this->ratings->where('rating', '2')->where('active','1')->count();
    }

    /*
    |
    | Get count of neutral ratings
    |
    */
    public function getNeutralRatingsAttribute()
    {
        return $this->ratings->where('rating', '1')->where('active','1')->count();
    }

    /*
    |
    | Get count of negative ratings
    |
    */
    public function getNegativeRatingsAttribute()
    {
        return $this->ratings->where('rating', '0')->where('active','1')->count();
    }

    /*
    |
    | Get url to profile
    |
    */
    public function getUrlAttribute()
    {
        return url('@' . strtolower($this->name));
    }

    /*
    |
    | Check if user is Online
    |
    */
    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /*
    |
    | Save last acitivity (fire every 5 minutes)
    |
    */
    public function lastActivity($timestamp)
    {
        $this->last_activity_at = $timestamp;
        $this->timestamps = false;
        $this->save();
        $this->timestamps = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }

    /**
     * @param $provider
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
    }
}
