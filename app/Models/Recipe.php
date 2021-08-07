<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tags',
        'serves',
        'time',
        'description',
        'ingredients',
        'keyEquipment',
        'headNote',
        'instructions',
        'imageUrl',
        'is_published',
        'publish_date'
    ];

    protected $casts = [
        'tags' => 'array',
        'ingredients' => 'array',
        'keyEquipment' => 'array',
        'instructions' => 'array',
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    // public function setPublishDate($value)
    // {
    //     $this->attributes['publish_date'] = Carbon::parse($value)->format('Y-m-d');
    // }
}
