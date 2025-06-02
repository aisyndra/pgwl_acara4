<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use Illuminate\Validation\Rule;


class PolygonsController extends Controller
{
    public function __construct()
    {
        $this->polygon = new PolygonsModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
                    'title' => 'Map',
                ];

                return view('map', $data);
            }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:polygon,name',
                'description' => 'required',
                'geom_polygon'=> 'required',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Geometry polygon is required',
            ]
            );

              // Create images directory if not exist
         if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }


       $data = [
        'geom'=> $request->geom_polygon,
        'name'=> $request->name,
        'description'=> $request-> description,
        'image' => $name_image,
        'user_id' => auth()->user()->id,
       ];

        //insert
    if (!$this->polygon->create($data)) {
        return redirect()->route('map')->with('Error', 'Polygon Failed To Add');
    };

    //redirect to map

    return redirect()->route('map')->with('success', 'Polygon has been added');

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
            'title' => 'Edit Polygon',
            'id' => $id,
        ];

        return view('edit-polygon', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $request->validate(
            [
                'name' => [
                    'required',
                    Rule::unique('polygon', 'name')->ignore($id),
                ],
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Geometry point is required',
            ]
        );

        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        $name_image = null;
        //get old image
       $old_image = $this->polygon->find($id)->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
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
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($name_image !== null) {
            $data['image'] = $name_image;
        }

        if (!$this->polygon->find($id)->update($data)) {
            return redirect()->route('map')->with('Error', 'Polygon Failed To update');
        }

        return redirect()->route('map')->with('success', 'Polygon has been updated');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polygon->find($id)->image;

        if(!$this->polygon->destroy($id)) {
        return redirect()->route('map')->with('error', 'Polygon failed to delete');
        }

        //DELETE IMAGEFILE
        if ($imagefile !=null){
        if (file_exists('./storage/images/'. $imagefile)) {
             unlink('./storage/images/'. $imagefile);
         }
        }

        return redirect()->route('map')->with('success', 'Polygon has been delete');
    }
}
