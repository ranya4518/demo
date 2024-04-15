<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'full_name'=>'required|max:50',
            'birthdate'=>'required|date',
            'email' => 'required|string|email|unique:users',
            'fcm_token'=>'required',
            'phone' => 'required|string|digits:11|unique:profiles',
            'password' => 'required|min:8|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
        $user =User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        ]);  
        $profile = new Profile([
            'full_name' => $request->full_name,
            'birthdate' => $request->birthdate,
            'phone' => $request->phone,
            'fcm_token'=>$request->fcm_token,
        ]);

        // Assign the user_id to the profile
        $profile->user_id = $user->id;

        // Save profile
        $profile->save();
         return response()->json(['message' => 'User registered successfully'], 200);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|min:8|string',
            'fcm_token' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 401);
        }
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password is not correct'], 401);
        }
    
        $user->tokens()->delete();
    
        // Profil ilişkisini kontrol etmek için
        $profile = $user->profile;
    
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
    
        $profile->fcm_token = $request->fcm_token;
        $profile->save();
    
        return response([
            'message' => 'User logged in successfully',
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }
    

public function logout(Request $request){
if ($request->user()) {
 $request->User()->currentAccessToken()->delete();
 return response()->json(['message'=>'user logged out successfully']);
    } else {
    return response()->json(['message' => 'Oturum açmış bir kullanıcı yok'], 401);
    }
}

public function updateEmail(Request $request){
    $user = Auth::user(); 
    $request->validate([
        'email' => 'required|email|unique:users',
    ]);
    $user->email = $request->email;
    /** @var \App\Models\User $user **/
     $user->save();
     return response()->json(['message'=>'email addresiniz güncellendi']);
}
public function changePassword(Request $request) {
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();
    $user->password = bcrypt($request->password);
    /** @var \App\Models\User $user **/
    
    $user->save();

    return response()->json(['message'=>'Şifreniz başarıyla değiştirildi.']);
}

}
