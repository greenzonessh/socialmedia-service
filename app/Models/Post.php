<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='posts';
    protected $primaryKey='id';
    protected $fillable=[
        'id',
        'profile_id',
        'caption'
    ];

    public $timestamps=true;

    public static $rules=[
        'create' => [
            'caption',
            'url_image' => 'required|array|min:1',
            'url_image.*' => 'required|string'
        ],
        'update' => [
            'post_id' => 'required|exists:posts,id',
            'caption'
        ]
    ];

    public function image()
    {
        return $this->hasMany(PostImage::class, 'post_id','id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class,'profile_id','id');
    }
}
