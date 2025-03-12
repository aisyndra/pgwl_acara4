<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
   //menambah tabel poin agar terhubung
   protected $table = 'polygon';

   //agar kolom id tercegah dari pengisian krn id otomatis mengurutkan data
   protected $guarded = ['id'];
}

