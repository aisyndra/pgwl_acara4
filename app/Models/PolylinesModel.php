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
        $polylines = DB::table($this->table)
            ->selectRaw("
                id,
                ST_AsGeoJSON(geom) AS geom,
                name,
                description,
                ST_Length(geom, true)::numeric AS length_m,
                ST_Length(geom, true) / 1000 AS length_km,
                created_at,
                updated_at
            ")
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
                    'name' => $p->name,
                    'description' => $p->description,
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
