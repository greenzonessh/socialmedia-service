<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostLike extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='post_likes';
    protected $primaryKey='id';
    protected $fillable=[
        'id',
        'post_id',
        'profile_id'
    ];

    public $timestamps=true;

    public static $rules=[
        'create' => [
            'post_id' => 'required|exists:posts,id',
            'profile_id' => 'required|exists:profiles,id'
        ],
        'update' => [
            'post_id' => 'required|exists:posts,id',
            'profile_id' => 'required|exists:profiles,id'
        ]
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }
}
