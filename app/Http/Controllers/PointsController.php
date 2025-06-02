<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PointsController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
    }

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

        //Validate request
        $request->validate(
            [
                'name' => 'required|unique:points,name',
                'description' => 'required',
                'geom_points' => 'required',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_points.required' => 'Geometry point is required',
            ]
        );

        // Create images directory if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }



        $data = [
            'geom' => $request->geom_points,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,

        ];


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
            'title' => 'Edit Point',
            'id' => $id,
        ];

        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($id, $request->all()); untuk mengecek
        $request->validate(
            [
                'name' => [
                    'required',
                    Rule::unique('points', 'name')->ignore($id),
                ],
                'description' => 'required',
                'geom_points' => 'required',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_points.required' => 'Geometry point is required',
            ]
        );

        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        $name_image = null;
        //get old image
       $old_image = $this->points->find($id)->image;

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
            'geom' => $request->geom_points,
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($name_image !== null) {
            $data['image'] = $name_image;
        }

        if (!$this->points->find($id)->update($data)) {
            return redirect()->route('map')->with('Error', 'Point Failed To update');
        }

        return redirect()->route('map')->with('success', 'Point has been updated');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->points->find($id)->image;

        if (!$this->points->destroy($id)) {
            return redirect()->route('map')->with('error', 'Point failed to delete');
        }

        //DELETE IMAGEFILE
        if ($imagefile != null) {
            if (file_exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Point has been delete');
    }
}
