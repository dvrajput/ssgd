<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_category_code',
        'sub_category_en',
        'sub_category_gu',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'song_sub_cate_rels');
    }
}
