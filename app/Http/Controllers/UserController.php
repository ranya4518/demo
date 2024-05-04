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

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'full_name'=>'required|max:50',
            'email' => 'required|string|email|unique:users',
            'fcm_token'=>'nullablephp ',
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
            'fcm_token' => 'nullable',
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
            'user'=>$user,
            'profile'=>$profile,
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

    // İsteği doğrulama
    $request->validate([
        'email' => 'required|email|unique:users',
        'current_email' => 'required|email|exists:users,email', // Eski e-posta adresini doğrulama
    ]);

    // Eğer eski e-posta adresi doğruysa, güncelleme işlemine izin ver
    if ($user->email === $request->current_email) {
        $user->email = $request->email;
        /** @var \App\Models\User $user **/
        $user->save();
        return response()->json(['message'=>'E-posta adresiniz güncellendi']);
    } else {
        // Eğer eski e-posta adresi doğru değilse, hata mesajı döndür
        return response()->json(['error'=>'Eski e-posta adresi doğrulanamadı'], 400);
    }
}

public function changePassword(Request $request) {
    $request->validate([
        'current_password' => 'required', // Mevcut şifre alınması zorunludur
        'password' => 'required|min:8|confirmed', // Yeni şifre en az 8 karakterden oluşmalı ve teyit edilmeli
        'password_confirmation' => 'required|same:password',
    ]);

    // Mevcut oturumda giriş yapmış olan kullanıcı alınıyor
    $user = Auth::user();

    // Mevcut şifre doğrulanıyor
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'Mevcut şifre hatalı.'], 400);
    }

    // Yeni şifre ve tekrarı doğrulanıyor
    if ($request->password!== $request->password_confirmation) {
        return response()->json(['message' => 'Yeni şifre ve tekrarı eşleşmiyor.'], 400);
    }

    // Şifre güncelleniyor
    $user->password = bcrypt($request->password);
    /** @var \App\Models\User $user **/

    $user->save();

    return response()->json(['message' => 'Şifreniz başarıyla değiştirildi.']);
}
public function showUserWithProfile() {
    // Mevcut oturumda giriş yapmış olan kullanıcıyı alıyoruz
    $user = auth()->user();

    // Kullanıcı varsa, profiliyle birlikte döndürüyoruz
    if ($user) {
        // Kullanıcının ilişkili profil bilgisini direkt olarak çağırıyoruz
        $profile = $user->profile;

        return response()->json(['user' => $user]);

    } else {
        return response()->json(['error' => 'Kullanıcı oturumu bulunamadı'], 404);
    }
}



}
