<?php

namespace App\Http\Controllers;

class DonorController extends Controller
{
   public function index() {
       return response()->json([
           "test" => "Hello World!"
       ]);
   }

   public function create() {


       return response()->json();
   }
}
