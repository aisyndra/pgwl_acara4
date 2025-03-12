<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    //menambah tabel poin agar terhubung
    protected $table = 'points';

    //agar kolom id tercegah dari pengisian krn id otomatis mengurutkan data
    protected $guarded = ['id'];
}
