<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Validator;
use App\Models\Blog;

class BlogController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->get();
        return response()->json(['status'=> true, 'blogs' => $blogs], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|unique:blogs',
            'description' => 'required|min:5',
            'image' => 'nullable|mimes:jpeg,jpg,png'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'message' => $validator->errors()],400);
        }
            $data = $request->except('_token', 'image');

            if($request->hasfile('image'))
            {
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('blog_images'), $imageName);
                $data['image'] = $imageName;
            }
            $data['user_id'] = Auth::user()->id;
            // Create a new blog blog
            $blog =  Blog::create($data);
            return response()->json(['status'=> true, 'message' => 'Blog created successfully', 'blog' => $blog],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);
        return response()->json(['status'=> true, 'blog' => $blog], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|unique:blogs',
            'description' => 'required|min:5',
            'image' => 'nullable|mimes:jpeg,jpg,png'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'message' => $validator->errors()],400);
        }

            $data = $request->except('_token', '_method', 'image');
            $blog = Blog::find($id);
            if($request->hasfile('image'))
            {
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('blog_images'), $imageName);
                $data['image'] = $imageName;
            }
            
            $blog->update($data);
            return response()->json(['status'=> true, 'message' => 'Blog updated successfully'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully'], 200);

    }

}
