<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','shop_name','address','logo_path','photo_path'];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
}