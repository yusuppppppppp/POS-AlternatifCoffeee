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
        'category',
        'image_path', // kolom ini menyimpan path di storage/public
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Tambahkan accessor agar bisa akses menu.image_url
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
