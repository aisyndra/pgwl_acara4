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

   public function geojson_polygons()
    {
        $polygon = $this->select(DB::raw('polygon.id, polygon.user_id, st_asgeojson(geom) as geom, polygon.name, polygon.description, polygon.image,
        st_area (geom,true) as area_m2, st_area(geom, true)/1000000 as area_km2 , st_area(geom, true)/10000 as area_ha,
        polygon.created_at, polygon.updated_at, users.name as user_created'))
        ->leftjoin('users', 'polygon.user_id', '=', 'users.id')
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
                'id'=> $p ->id,
                'name' => $p->name,
                'description' => $p->description,
                'image'=> $p ->image,
                'area_m2' => $p->area_m2,
                'area_km2' => $p->area_km2,
                'area_ha' => $p->area_ha,
                'created_at' => $p ->created_at,
                'updated_at' => $p ->updated_at,
                'user_id' => $p->user_id,
                'user_created' => $p->user_created,
            ],

        ];

        array_push($geojson['features'], $feature);

    }

    return $geojson;

}
   public function geojson_polygon($id)
    {
        $polygon = $this->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image,
        st_area (geom,true) as area_m2, st_area(geom, true)/1000000 as area_km2 , st_area(geom, true)/10000 as area_ha,
        created_at, updated_at'))
        ->where('id', $id)
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
                'id'=> $p ->id,
                'name' => $p->name,
                'description' => $p->description,
                'image'=> $p ->image,
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

