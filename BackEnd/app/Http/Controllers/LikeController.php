<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
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
        $likes = like::where(['user_id'=>$request->user_id,'post_id'=>$request->post_id])->first();
        if($likes===null){
            like::create([
                'user_id' => $request->user_id,
                'post_id' => $request->post_id,
            ]);
            Post::where(['id'=>$request->post_id])->increment('likes');
            return response()->json(['message' => 'Like created successfully']);
        }else{
            $likes->delete();
            Post::where(['id'=>$request->post_id])->decrement('likes');
            return response()->json(['message' => 'Like deleted successfully']);
        }
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
    public function destroy(like $like)
    {
        // $like->delete();
        // return response()->json(['message' => 'Like deleted successfully']);
    }

    public function getUserLikes($user_id)
    {
        $likes = like::where(['user_id'=>$user_id])->get();
        return response()->json($likes);
    }
}
