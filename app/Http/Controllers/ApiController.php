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


    public function polylines()
    {
        $polylines = $this->polylines()->geojson_polylines();

        return response()->json($polylines);

        //LUASAN DIKEMBALIKAN DALAM BENTUK NUMERIK
        return response()->json($polylines, 200, [], JSON_NUMERIC_CHECK);
    }

    public function polygon()
    {
        $polygon = $this->polygon()->geojson_polygon();

        return response()->json($polygon);

         //LUASAN DIKEMBALIKAN DALAM BENTUK NUMERIK
         return response()->json($polygon, 200, [], JSON_NUMERIC_CHECK);


    }
}
