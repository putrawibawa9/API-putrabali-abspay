<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
   public function login(Request $request)
{
    // dd($request->all());
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string',
        'password' => 'required|string',
    ]);

    // Attempt to authenticate the user using the 'name' field
    if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
        // return the user data
        $user = Auth::user();
        return response()->json(['message' => 'Login successful', 'user'=> $user], 200);
    }

    // Authentication failed
    return response()->json(['message' => 'Invalid credentials'], 401);
}


    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        // return 201 with no message
        return response()->json([], 201);
    }

   public function loginTeacher(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Retrieve teacher record based on username
    $teacher = Teacher::where('username', $request->username)->first();

    if (!$teacher || !Hash::check($request->password, $teacher->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'message' => 'Login successful',
        'teacher' => $teacher,
    
    ]);
}

}
