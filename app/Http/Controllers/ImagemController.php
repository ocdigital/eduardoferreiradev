<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImagemController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function index()
    {
        $images = Image::all();
        
        return view('images',['images' => $images]);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = time() . '.' . $request->image->extension();

            $path = Storage::disk('s3')->put('images', $request->image);
            $path = Storage::disk('s3')->url($path);
      
       
        $image = Image::create([
            'name' => basename($path),
            'url'  => Storage::disk('s3')->url($path),
        ]);
        return $image;

    }
    
    public function show(Image $image)
    {
        return Storage::disk('s3')->response('public/categories/'.$image->name);
    }
}
