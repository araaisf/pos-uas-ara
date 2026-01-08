<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Mengizinkan semua kolom diisi (Mass Assignment)
    protected $guarded = [];

    // Relasi: Satu transaksi punya banyak detail barang
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}