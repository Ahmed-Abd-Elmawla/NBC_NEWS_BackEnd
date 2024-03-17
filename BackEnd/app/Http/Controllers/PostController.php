<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Article;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])->get();
        return response()->json($posts);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function getPostsByStatus($status)
    {
        $posts = Post::where('status', $status)->with(['user', 'category'])->get();
        return response()->json($posts);
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
        $request->validate([
            "user_id" => 'required|integer',
            "category_id" => 'required|integer',
            "title" => 'required|string',
            "cover" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "sub_title" => "required|string",
            "paragraph_1" => "required|string",
            "location" => "required|string",
            //Article validation
            "image_1" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "paragraph_2" => "required|string",
            "image_source_1" => "required|string",
            "details_1" => "required|string",
            "image_2" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "paragraph_3" => "required|string",
            "image_source_2" => "required|string",
            "details_2" => "required|string",
        ]);

        $file = $request->file('cover');
        $fileName = time() . $file->getClientOriginalName();
        $file->move(public_path('posts_images'), $fileName);

        $timeToRead = $this->calculateReadingTime($request->paragraph_1, $request->paragraph_2, $request->paragraph_3);
        $tags = [];
        if (!empty($request->tags)) {
            $tags = explode(',', $request->tags);
            // $tags = array_map('trim', $tags);
        }


        $post = Post::create([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'cover' => $fileName,
            'sub_title' => $request->sub_title,
            'description' => $request->paragraph_1,
            'time_to_read' => $timeToRead,
            'location' => $request->location,
            'tags' => $tags
        ]);

        $post_id = $post->id;

        $file_1 = $request->file('image_1');
        $fileName_1 = time() . $file_1->getClientOriginalName();
        $file_1->move(public_path('posts_images'), $fileName_1);

        $file_2 = $request->file('image_2');
        $fileName_2 = time() . $file_2->getClientOriginalName();
        $file_2->move(public_path('posts_images'), $fileName_2);

        Article::create([
            "post_id" => $post_id,
            "image" => $fileName_1,
            "description" => $request->paragraph_2,
            "image_source" => $request->image_source_1,
            "details" => $request->details_1,
        ]);
        Article::create([
            "post_id" => $post_id,
            "image" => $fileName_2,
            "description" => $request->paragraph_3,
            "image_source" => $request->image_source_2,
            "details" => $request->details_2,
        ]);

        return response()->json(['message' => 'Post created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($post)
    {
        $post_ = Post::with(['user', 'category', 'articles'])->findOrFail($post);
        return $post_;
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
    public function update(Request $request,  Post $post, Article $article_1, Article $article_2)
    {
        // if ($request->has('likes')) {
        //     if (filter_var($request->input('likes'), FILTER_VALIDATE_BOOLEAN)) {
        //         $post->increment('likes');
        //         return response()->json(['message' => 'Likes incremented successfully', 'likes' => $post->likes]);
        //     } else {
        //         $post->decrement('likes');
        //         return response()->json(['message' => 'Likes decremented successfully', 'likes' => $post->likes]);
        //     }
        // }

        $request->validate([
            "user_id" => 'required|integer',
            "category_id" => 'required|integer',
            "title" => 'required|string',
            // "cover" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "sub_title" => "required|string",
            "paragraph_1" => "required|string",
            "location" => "required|string",
            //Article validation
            // "image_1" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "paragraph_2" => "required|string",
            "image_source_1" => "required|string",
            "details_1" => "required|string",
            // "image_2" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "paragraph_3" => "required|string",
            "image_source_2" => "required|string",
            "details_2" => "required|string",
        ]);

        // if ($request->has('cover')) {
        //     $img = $post->cover;
        //     $imagePath = public_path('posts_images/' . $img);
        //     if (file_exists($imagePath)) {
        //         unlink($imagePath);
        //     }
        //     $new_image = $request->file('cover');
        //     $image_name = time() . $new_image->getClientOriginalName();
        //     $new_image->move(public_path('posts_images'), $image_name);
        //     $post->update([
        //         'cover' => $image_name,
        //     ]);
        // };

        $post->update([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            // 'cover' => $image_name,
            'sub_title' => $request->sub_title,
            'description' => $request->paragraph_1,
            'location' => $request->location,
        ]);

        $article_1->update([
            "description" => $request->paragraph_2,
            "image_source" => $request->image_source_1,
            "details" => $request->details_1,
        ]);

        $article_2->update([
            "description" => $request->paragraph_3,
            "image_source" => $request->image_source_2,
            "details" => $request->details_2,
        ]);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!empty($post->cover)) {
            $img = $post->cover;
            $imagePath = public_path('posts_images/' . $img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $post->articles()->delete();
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * Get posts by user_id.
     */
    public function getByUserId($user_id)
    {
        $posts = Post::with(['articles'])->where('user_id', $user_id)->get();
        return response()->json($posts);
    }

    /**
     * Get posts by category_id.
     */
    public function getByCategoryId($category_id)
    {
        $posts = Post::with(['user', 'category'])->where(['category_id' => $category_id, 'status' => 'published'])->get();
        return response()->json($posts);
    }

    /**
     * Function to calculate the estimated reading time
     */
    public function calculateReadingTime($p1, $p2, $p3)
    {
        $wordsPerMinute = 200; // Average reading speed in words per minute
        $totalWords = str_word_count($p1) + str_word_count($p2) + str_word_count($p3);
        return ceil($totalWords / $wordsPerMinute);
    }

    /**
     * update post status
     */
    public function updateStatus(Request $request, Post $post)
    {
        switch ($request->status) {
            case 'trending':
                Post::where('status', 'trending')->update(['status' => 'published']);
                $post->update(['status' => $request->status]);
                return response()->json(['message' => 'Post status updated successfully']);
                // break;
            case 'breakingNews':
                Post::where('status', 'breakingNews')->update(['status' => 'published']);
                $post->update(['status' => $request->status]);
                return response()->json(['message' => 'Post status updated successfully']);
                // break;
            case 'live':
                Post::where('status', 'live')->update(['status' => 'published']);
                $post->update(['status' => $request->status]);
                return response()->json(['message' => 'Post status updated successfully']);
                // break;
            default:
                $post->update(['status' => $request->status]);
                return response()->json(['message' => 'Post status updated successfully']);
                // break;
        }
    }
}
