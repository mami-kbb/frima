<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profile_image', 'postal_code', 'address', 'building'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image
            ? asset('storage/'.$this->profile_image)
            : asset('images/default_user.png');
    }
}
