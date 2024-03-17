<?php

namespace App\Http\Controllers;

use App\Models\Save;
use Illuminate\Http\Request;

class SaveController extends Controller
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
        $saves = Save::where(['user_id'=>$request->user_id,'post_id'=>$request->post_id])->get();
        if($saves->isEmpty()){
            Save::create([
                'user_id' => $request->user_id,
                'post_id' => $request->post_id,
            ]);
            return response()->json(['message' => 'Save created successfully']);
        }else{
            return response()->json(['message' => 'false']);
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
    public function destroy(Save $save)
    {
        $save->delete();
        return response()->json(['message' => 'Save deleted successfully']);
    }

    public function getUserSaves($user_id)
    {
        $saves = Save::where(['user_id'=>$user_id])->with(['post'])->get();
        return response()->json($saves);
    }
}
