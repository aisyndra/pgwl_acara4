<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
   //menambah tabel poin agar terhubung
   protected $table = 'polygon';

   //agar kolom id tercegah dari pengisian krn id otomatis mengurutkan data
   protected $guarded = ['id'];

   public function geojson_polygon()
    {
        $polygon = $this->select(DB::raw('st_asgeojson(geom) as geom, name, description,
        st_area (geom,true) as area_m2, st_area(geom, true)/1000000 as area_km2 , st_area(geom, true)/10000 as area_ha,
        created_at, updated_at'))
        ->get();

    $geojson =[
        'type' => 'FeatureCollection',
        'features' => [],
    ];

    foreach ($polygon as $p) {
        $feature = [
            'type' => 'Feature',
            'geometry' => json_decode($p->geom),
            'properties' => [
                'name' => $p->name,
                'description' => $p->description,
                'area_m2' => $p->area_m2,
                'area_km2' => $p->area_km2,
                'area_ha' => $p->area_ha,
                'created_at' => $p ->created_at,
                'updated_at' => $p ->updated_at,
            ],

        ];

        array_push($geojson['features'], $feature);

    }

    return $geojson;

}
}

