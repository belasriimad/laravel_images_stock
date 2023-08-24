<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    //
    public function dashboard()
    {
        return view('photos.user.dashboard');
    }

    public function updateProfileImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        if(File::exists(auth()->user()->profile_image)) {
            File::delete(auth()->user()->profile_image);
        }
        $image = $request->file('image');
        $image_name = time().'_'.'image'.'_'.$image->getClientOriginalName();
        $image->storeAs('profiles/', $image_name, 'public');
        auth()->user()->update([
            'profile_image' => 'storage/profiles/'.$image_name
        ]);
        return redirect()->route('dashboard')->with([
            'success' => 'Profile image updated successfully'
        ]);
    }
}
