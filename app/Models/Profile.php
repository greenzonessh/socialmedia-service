<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='profiles';
    protected $primaryKey='id';
    protected $fillable=[
        'id',
        'user_id',
        'firstname',
        'lastname',
        'dob',
        'url_image',
        'phonenumber',
    ];

    public $timestamps=true;

    public static $rules=[
        'create' => [
            'firstname' => 'required|min:3|max:120',
            'lastname' => 'required|min:2|max:120',
            'dob' => 'required|date',
            'url_image',
            'phonenumber' => 'required|min:10|max:13',
        ],
        'update' => [
            'id' => 'required|exists:profiles,id',
            'firstname' => 'required|min:3|max:120',
            'lastname' => 'required|min:2|max:120',
            'dob' => 'required|date',
            'url_image',
            'phonenumber' => 'required|min:10|max:13',
        ]
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function profileFollower()
    {
        return $this->hasMany(ProfileFollowing::class,'profile_id', 'id');
    }

    public function profileFollowing()
    {
        return $this->hasMany(ProfileFollowing::class,'profile_id', 'id');
    }
}
