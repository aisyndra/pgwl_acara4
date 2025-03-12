<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    //menambah tabel poin agar terhubung
    protected $table = 'polyline';

    //agar kolom id tercegah dari pengisian krn id otomatis mengurutkan data
    protected $guarded = ['id'];

}
