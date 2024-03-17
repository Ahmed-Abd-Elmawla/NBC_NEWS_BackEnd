<?php

namespace App\Http\Controllers;

use App\Mail\mailer;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {   $request->validate([
        'name' => 'required|string|min:10|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|min:8',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example: Maximum file size of 2MB
    ]);

    try {
        $file = $request->file('image');
        $fileName = time() . $file->getClientOriginalName();
        $file->move(public_path('users_images'), $fileName);

        $user = User::create([
            'name' => $request->name,
            'image' => $fileName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 102,
        ]);

        Auth::login($user);

        return response()->json([
            'message' => 'User created successfully',
            'user' => auth()->user(),
        ]);
    } catch (\Exception $e) {
        Log::error($e);
        return response()->json(['error' => 'User creation failed'], 500);
    }}

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
                return response()->json(auth()->user());
        } else {
            return response()->json(['message' => false]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user=User::find($id);
        return response()->json($user);
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

    public function updateUser(Request $request, User $user)
    {
        if ($request->has('role_id')) {
            $user->update([
                'role_id' => $request->role_id
            ]);
            return response()->json(['message' => 'Role updated successfully']);
        };

        $request->validate([
            'name' => 'required|string|min:10|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            "image" => "nullable|image",
        ]);

        if ($request->has('image')) {
            $img = $user->image;
            $imagePath = public_path('users_images/' . $img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $new_image = $request->file('image');
            $image_name = time() . $new_image->getClientOriginalName();
            $new_image->move(public_path('users_images'), $image_name);
            $user->update([
                'image' => $image_name,
            ]);
        };

        if ($request->has('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        };

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return response()->json(['message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!empty($user->image)) {
            $img = $user->image;
            $imagePath = public_path('users_images/' . $img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

    $user->posts()->delete();

    $user->delete();
        return response()->json(['message' => 'user deleted successfully']);
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }
    public function searchByMail($email)
    {
        $user = User::where(['email' => $email])->first();
        if ($user) {
            $code = str_pad(mt_rand(111111, 999999), 6, '0', STR_PAD_LEFT);
            $user->remember_token = $code;
            try {
                $user->save();
            } catch (\Exception $e) {
                return response()->json(['message' => false, 'error' => $e->getMessage()]);
            }
            $data['subject'] = 'Verification Code';
            $data['title'] = $code;
            $data['message'] = 'this yor Verification code';
            Mail::to($email)->send(new mailer($data));
            return response()->json(['message' => true]);
        } else {
            return response()->json(['message' => false]);
        }
    }

    public function checkCode($email, $code)
    {
        $user = User::where(['email' => $email])->first();
        if ($user->remember_token == $code) {
            return response()->json(['message' => true]);
        } else {
            return response()->json(['message' => false]);
        }
    }


    public function resetPassword(Request $request, $email)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);
        $user = User::where(['email' => $email])->first();
        $user->remember_token = null;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'password updated successfully.']);
    }
}
