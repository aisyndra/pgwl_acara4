<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use App\Models\PolylinesModel;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points= new PointsModel();
        $this->polylines = new PolylinesModel();
        $this->polygon = new PolygonsModel();
    }

    public function points()
    {
        $points = $this->points->geojson_points();

        return response()->json($points);
    }
    public function point($id)
    {
        $points = $this->points->geojson_point($id);

        return response()->json($points);
    }


    public function polylines()
    {
        $polylines = $this->polylines->geojson_polylines();

        return response()->json($polylines);

        //LUASAN DIKEMBALIKAN DALAM BENTUK NUMERIK
        return response()->json($polylines, 200, [], JSON_NUMERIC_CHECK);
    }

    public function polyline($id)
    {
        $polylines = $this->polylines->geojson_polyline($id);

        return response()->json($polylines);
        //LUASAN DIKEMBALIKAN DALAM BENTUK NUMERIK
        return response()->json($polylines, 200, [], JSON_NUMERIC_CHECK);
    }

    public function polygons()
    {
        $polygon = $this->polygon->geojson_polygons();


         //LUASAN DIKEMBALIKAN DALAM BENTUK NUMERIK
         return response()->json($polygon, 200, [], JSON_NUMERIC_CHECK);


    }
    public function polygon($id)
    {
        $polygon = $this->polygon->geojson_polygon($id);

         //LUASAN DIKEMBALIKAN DALAM BENTUK NUMERIK
         return response()->json($polygon, 200, [], JSON_NUMERIC_CHECK);


    }
}
