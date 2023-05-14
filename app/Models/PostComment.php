<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='post_comments';
    protected $primaryKey='id';
    protected $fillable=[
        'id',
        'post_id',
        'profile_id',
        'comment'
    ];

    public $timestamps=true;

    public static $rules=[
        'create' => [
            'post_id' => 'required|exists:posts,id',
            'profile_id' => 'required|exists:profiles,id',
            'comment' => 'required'
        ],
        'update' => [
            'id' => 'required|exists:post_comments,id',
            'post_id' => 'required|exists:posts,id',
            'profile_id' => 'required|exists:profiles,id',
            'comment' => 'required'
        ]
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }
}
