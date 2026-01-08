<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Ini kuncinya: Izinkan semua kolom diisi, KECUALI yang aneh-aneh
    protected $guarded = []; 
}