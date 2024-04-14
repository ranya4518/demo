<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
 public function updateprofile(Request $request){
    $validator = Validator::make($request->all(), [
        'full_name'=>'required|max:50',
        'birthdate'=> 'required|date',
        'phone' => 'required|string|digits:11|unique:profiles',
    ]);
    $user =User::findOrFail(auth()->id()); // Kullanıcıyı bul
    $user->profile()->update($request->all()); // Profil bilgilerini güncelle

    return response()->json(['message' => 'Profil güncellendi'], 200);
 }
 public function show()
    {
        $user = auth()->user();

        if ($user) {
            // Kullanıcıya ait profil bilgilerini bul
            $profile = Profile::where('user_id', $user->id)->first();

            // Eğer profil bulunduysa JSON olarak döndür
            if ($profile) {
                return response()->json($profile);
            } else {
                return response()->json(['error' => 'Profil bilgisi bulunamadı'], 404);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
