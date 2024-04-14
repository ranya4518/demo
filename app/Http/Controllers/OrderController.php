<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Details;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
public function createOrder(Request $request){
    $userId =Auth::user()->id;
    // flutter api ile gelen ürün listesi sepet sayfasından body ile json formatında
    //aldık ve $products içinde kaydettik artık buradan ürünlere dongu ile erişebiliriz
    $products = $request->input('products');
    // bir order oluşturduk
    $order = new Order;
    $order->user_id = $userId;
    $order->total = 0; // Toplam fiyatı daha sonra hesaplayacağız
    $order->save();
    // Stok güncelleme işlemi
    foreach ($products as $product) {
        $productID = $product['product_id'];
        $quantity = $product['quantity'];

        // Ürünü bul
        $product = Product::find($productID);
        if (!$product) {
            return response()->json(['error' => 'Ürün bulunamadı'], 404);
        }

        // Sipariş miktarı kadar stoktan düşme işlemi yap
        $product->stock -= $quantity;
        $product->save();
    }
        // Sipariş detaylarını oluştur
    // dögü ile her ürün için bir ürün detayı oluşturduk
    foreach ($products as $product) {
        $orderDetail = new Order_Details;
        $orderDetail->order_id = $order->id;
        $orderDetail->product_id = $product['product_id'];
        $orderDetail->quantity = $product['quantity'];
        $orderDetail->price = $product['price'];
        $orderDetail->save();
    }
    // ürünlerin fiyatları calculateOrderTotalPrice metodu ile hesaplıyoruz ve 
    //bu metoda order idsi gönderiyoruz
    $orderTotalPrice = $this->calculateOrderTotalPrice($order->id);
    $order->total = $orderTotalPrice;
    $order->save();
    return response()->json(['message' => 'Sipariş başarıyla oluşturuldu.']);
   
}
private function calculateOrderTotalPrice($orderId) {
    $orderDetails = Order_Details::where('order_id', $orderId)->get();
    $totalPrice = 0;
    foreach ($orderDetails as $orderDetail) {
        $totalPrice += $orderDetail->price * $orderDetail->quantity;
    }
    return $totalPrice;
}
 public function userOrders(){
  $userId=Auth::user()->id;
  $myOrders=Order::where('user_id',$userId)->get();
  return response()->json(['my orders'=>$myOrders]);
 }

 public function getOrderDetails($orderId) {
    $order = Order::with('orderDetails')->find($orderId);
    
    if (!$order) {
        return response()->json(['error' => 'Sipariş bulunamadı'], 404);
    }
    
    $orderDetails = $order->orderDetails;
    
    return response()->json($orderDetails);
}

}


