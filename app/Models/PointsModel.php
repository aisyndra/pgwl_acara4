<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    //menambah tabel poin agar terhubung
    protected $table = 'points';

    //agar kolom id tercegah dari pengisian krn id otomatis mengurutkan data
    protected $guarded = ['id'];

    public function geojson_points()
    {
        $points = $this->select(DB::raw('points.id, st_asgeojson(geom) as geom,
        points.name, points.description, points.image, points.created_at, points.updated_at, users.name as user_created'))
        ->leftjoin('users', 'points.user_id', '=', 'users.id')
        ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'user_id' => $p->user_id,
                    'user_created' => $p->user_created,
                ],

            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
    public function geojson_point($id)
    {
        $points = $this
        ->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image, created_at, updated_at'))
        ->where('id', $id)
        ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],

            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
