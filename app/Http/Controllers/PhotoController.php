<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;

class PhotoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $photos = Photo::latest()->get();
        return view('home')->with([
            'photos' => $photos
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('photos.user.upload');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {
        //
        if($request->validated()) {
            $data = $request->all();
            $image = $request->file('image');
            $image_name = time().'_'.'image'.'_'.$image->getClientOriginalName();
            $image->storeAs('images/', $image_name, 'public');
            $data['url'] = 'storage/images/'.$image_name;
            $data['user_id'] = auth()->user()->id;
            Photo::create($data);
            return redirect()->route('home')->with([
                'success' => 'Photo uploaded successfully'
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
          return view('photos.show')->with([
            'photo' => $photo
          ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
        $this->authorize('update', $photo);
        return view('photos.user.edit')->with([
            'photo' => $photo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        //
        if($request->validated()) {
            $data = $request->all();
            $photo->update($data);
            return redirect()->route('dashboard')->with([
                'success' => 'Photo updated successfully'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
