<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;

class PolylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Map',
        ];

        return view ('map', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate(
            [
                'name' => 'required|unique:points,name',
                'description' => 'required',
                'geom_points'=> 'required',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_points.required' => 'Geometry point is required',
            ]
            );


        // Insert data
        $data = [
            'geom'=> $request->geom_polyline,
            'name'=> $request->name,
            'description'=> $request-> description,
           ];

       //insert
    if (!$this->polylines->create($data)) {
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
