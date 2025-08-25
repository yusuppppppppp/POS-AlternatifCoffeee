<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'image_path', // kolom ini menyimpan path di storage/public
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Tambahkan accessor agar bisa akses menu.image_url
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
