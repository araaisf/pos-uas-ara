<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $guarded = []; // Ini trik biar semua kolom bisa diisi
    public function category()
{
    return $this->belongsTo(Category::class);
}
}