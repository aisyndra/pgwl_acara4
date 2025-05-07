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
            'geom_polyline' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000',
        ], [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polyline.required' => 'Geometry polylines is required',
            'geom_polyline.json' => 'Geometry must be in GeoJSON format',
        ]);

         // Create images directory if not exist
         if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        // Insert data ke database
        $data = [
            'geom' => $request -> geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // insert
if (!$this->polyline->create($data)) {
    return redirect()->route('map')->with('error', 'Polyline Failed To Add');
}

// redirect to map
return redirect()->route('map')->with('success', 'Polyline has been added');


    /**
     * Mengambil satu polyline berdasarkan ID
     */

    //insert
    if (!$this->points->create($data)) {
        return redirect()->route('map')->with('Error', 'Point Failed To Add');
    };

    //redirect to map

    return redirect()->route('map')->with('success', 'Point has been added');


    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Menghapus polyline berdasarkan ID
     */
    public function destroy($id)
    {
        $imagefile = $this->polyline->find($id)->image;

        if(!$this->polyline->destroy($id)) {
         return redirect()->route('map')->with('error', 'Polyline failed to delete');
        }

        //DELETE IMAGEFILE
        if ($imagefile !=null){
         if (file_exists('./storage/images/'. $imagefile)) {
             unlink('./storage/images/'. $imagefile);
         }
        }

        return redirect()->route('map')->with('success', 'Polyline has been delete');

    }
}
