<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='post_images';
    protected $primaryKey='id';
    protected $fillable=[
        'id',
        'post_id',
        'url_image'
    ];

    public $timestamps=true;

    public static $rules=[
        'create' => [
            'post_id' => 'required|exists:posts,id',
            'url_image' => 'required'
        ],
        'update' => [
            'id' => 'required|exists:post_images,id',
            'post_id' => 'required|exists:posts,id',
            'url_image' => 'required'
        ]
    ];
}
