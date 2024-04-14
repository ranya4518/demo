<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Details;
use Illuminate\Http\Request;

class Order_detailsController extends Controller
{
 public function index(){
$order=Order_Details::all();
return response()->json($order);
 }
public function orderdetails($id){
$orderdetails=Order_Details::with('product')->find($id);
return response()->json($orderdetails);
 }
}
