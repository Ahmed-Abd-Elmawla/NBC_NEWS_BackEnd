<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //
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
        // $request->validate([
        //     "post_id" => 'required|integer',
        //     "image" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        //     "description" => "string",
        //     "image_source" => "string",
        //     "details" => "text",
        // ]);

        // $file = $request->file('image');
        // $fileName = time() . $file->getClientOriginalName();
        // $file->move(public_path('posts_images'), $fileName);

        // Article::create([
        //     "post_id" => $request->post_id,
        //     "image" => $fileName,
        //     "description" => $request->description,
        //     "image_source" => $request->image_source,
        //     "details" => $request->details,
        // ]);
        // return response()->json(['message'=>'Article created successfully']);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
