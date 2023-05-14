<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileFollowing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='profile_followings';
    protected $primaryKey='id';

    protected $fillable=[
        'id',
        'profile_id',
        'profile_following_id'
    ];

    public $timestamps=true;

    public static $rules=[
        'create' => [
            'profile_id' => 'required|exists:profiles,id',
            'profile_following_id' => 'required|exists:profiles,id'
        ],
        'update' => [
            'id' => 'required|exists:profile_followers,id',
            'profile_id' => 'required|exists:profiles,id',
            'profile_following_id' => 'required|exists:profiles,id'
        ]
    ];

    public function profileFollowers()
    {
        return $this->belongsTo(Profile::class,'profile_id', 'id');
    }

    public function profileFollowings()
    {
        return $this->hasOne(Profile::class, 'id', 'profile_following_id');
    }
}
