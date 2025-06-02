<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PolylinesModel extends Model
{
    protected $table = 'polyline';
    protected $guarded = ['id'];

    public function geojson_polylines()
    {
        $polylines = $this->select(DB::raw("
                polyline.id,
                polyline.user_id,
                ST_AsGeoJSON(geom) AS geom,
                polyline.name,
                polyline.description,
                polyline.image,
                ST_Length(geom, true)::numeric AS length_m,
                ST_Length(geom, true) / 1000 AS length_km,
                polyline.created_at,
                polyline.updated_at,
                users.name as user_created
            "))
            ->leftjoin('users', 'polyline.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom, true, 512, JSON_THROW_ON_ERROR),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'length_m' => floatval($p->length_m),  // Pastikan jadi angka
                    'length_km' => floatval($p->length_km),  // Pastikan jadi angka
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'user_id' => $p->user_id,
                    'user_created' => $p->user_created,
                ],
            ];

            $geojson['features'][] = $feature;
        }

        return $geojson;
    }
    public function geojson_polyline($id)
    {
        $polylines = $this->select(DB::raw("
                id,
                ST_AsGeoJSON(geom) AS geom,
                name,
                description,
                image,
                ST_Length(geom, true)::numeric AS length_m,
                ST_Length(geom, true) / 1000 AS length_km,
                created_at,
                updated_at
            "))
            ->where('id', $id)
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom, true, 512, JSON_THROW_ON_ERROR),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'length_m' => floatval($p->length_m),  // Pastikan jadi angka
                    'length_km' => floatval($p->length_km),  // Pastikan jadi angka
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],
            ];

            $geojson['features'][] = $feature;
        }

        return $geojson;
    }
}
