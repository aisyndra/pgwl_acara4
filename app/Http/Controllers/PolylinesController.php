<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polyline = new PolylinesModel();
    }

    /**
     * Mengambil data semua polylines dalam format GeoJSON
     */
    public function index()
    {
        return response()->json($this->polyline->geojson_polylines());
    }

    /**
     * Menyimpan polyline baru ke database
     */
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'name' => 'required|unique:polyline,name',
            'description' => 'required',
            'geom_polyline' => 'required|json',
        ], [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polyline.required' => 'Geometry polylines is required',
            'geom_polyline.json' => 'Geometry must be in GeoJSON format',
        ]);

        // Insert data ke database
        $data = [
            'geom' => DB::raw("ST_GeomFromGeoJSON('" . $request->geom_polyline . "')"),
            'name' => $request->name,
            'description' => $request->description,
        ];

        // Coba insert ke database
        try {
            $this->polyline->create($data);
            return response()->json(['message' => 'Polyline has been added'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Polyline Failed To Add', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil satu polyline berdasarkan ID
     */
    public function show($id)
    {
        $polyline = $this->polyline->find($id);
        if (!$polyline) {
            return response()->json(['error' => 'Polyline not found'], 404);
        }
        return response()->json($polyline);
    }

    /**
     * Menghapus polyline berdasarkan ID
     */
    public function destroy($id)
    {
        $polyline = $this->polyline->find($id);
        if (!$polyline) {
            return response()->json(['error' => 'Polyline not found'], 404);
        }

        $polyline->delete();
        return response()->json(['message' => 'Polyline has been deleted']);
    }
}
