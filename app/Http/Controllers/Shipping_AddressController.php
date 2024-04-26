<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Shipping_Addresses;
use Illuminate\Support\Facades\Auth;
class Shipping_AddressController extends Controller
{
   
public function addAddress(Request $request)
{
    if (Auth::check()) {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'address_title' => 'required|max:50',
            'address_line_1' => 'required|max:255',
            'address_line_2' => 'nullable|max:255', 
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'zip_code' => 'required|max:10',
        ]);
        // Kullanıcı oturum açmışsa, kullanıcının ID'sini alabiliriz.
        $user =auth()->id();

        // ShippingAddress modeli üzerinden yeni bir kayıt oluşturuyoruz.
        $address =Shipping_Addresses::create([
            'user_id' =>$user,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address_title' =>$request->address_title,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
        ]);  
        // Başarılı bir şekilde kaydedildiyse, başarı mesajı döndürebiliriz.
        return response()->json(['message' => 'Adres başarıyla kaydedildi'], 200);
    } else {
        // Kullanıcı oturum açmamışsa, bir hata mesajı döndürebiliriz.
     return response()->json(['message' =>'İlk olarak giriş yapmanız gerekiyor'], 401);
    }
}
  public function destroy($id){
  $deleteAddress=Shipping_Addresses::find($id);
  $deleteAddress->delete();
  return response()->json(['message'=>'addresiniz başarıyla silinmiştir']);
}
public function MyAddress(){
 $user = Auth::user();
 $myaddress=Shipping_Addresses::where('user_id',$user->id)->get();
 return response()->json($myaddress);
}
public function updateAddress(Request $request,$id){
    if (Auth::check()) {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'address_title' => 'required|max:50',
            'address_line_1' => 'required|max:255',
            'address_line_2' => 'nullable|max:255', 
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'zip_code' => 'required|max:10',
        ]);
        $user =auth()->id();
       $addres=Shipping_Addresses::find($id);
       $addres->update($request->all());
        
        return response()->json(['message' => 'Adres başarıyla güncelleştirilmişti'], 200);
    } else {
        // Kullanıcı oturum açmamışsa, bir hata mesajı döndürebiliriz.
     return response()->json(['message' =>'İlk olarak giriş yapmanız gerekiyor'], 401);
    }  
  }
}

