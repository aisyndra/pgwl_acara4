<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB; // Pastikan ini ada

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polyline = new PolylinesModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Map',
        ];

        return view('map', $data);
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
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,

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
        $data = [
            'title' => 'Edit Polyline',
            'id' => $id,
        ];

        return view('edit-polyline', $data);
    }


    public function update(Request $request, string $id)
    {
        // dd($id, $request->all()); untuk mengecek
        $request->validate(
            [
                'name' => [
                    'required',
                    Rule::unique('polyline', 'name')->ignore($id),
                ],
                'description' => 'required',
                'geom_polyline' => 'required',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Geometry polyline is required',
            ]
        );

        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        $name_image = null;
        //get old image
       $old_image = $this->polyline->find($id)->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            //Delete old image
            if ($old_image != null) {
                if(file_exists('./storage/images/' .$old_image))
            unlink('./storage/images/'. $old_image);
            }
        }
        //else {
         //   $name_image=$old_image;
        //}

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($name_image !== null) {
            $data['image'] = $name_image;
        }

        if (!$this->polyline->find($id)->update($data)) {
            return redirect()->route('map')->with('Error', 'Point Failed To update');
        }

        return redirect()->route('map')->with('success', 'Point has been updated');
    }



    /**
     * Menghapus polyline berdasarkan ID
     */
    public function destroy($id)
    {
        $imagefile = $this->polyline->find($id)->image;

        if (!$this->polyline->destroy($id)) {
            return redirect()->route('map')->with('error', 'Polyline failed to delete');
        }

        //DELETE IMAGEFILE
        if ($imagefile != null) {
            if (file_exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polyline has been delete');
    }
}
