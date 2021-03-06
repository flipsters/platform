<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{

    protected $table = 'comment_likes';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

  public function user()
  {
      return $this->belongsTo('App\Models\User');
  }

  public function comment()
  {
      return $this->belongsTo('App\Models\Comment');
  }

}
