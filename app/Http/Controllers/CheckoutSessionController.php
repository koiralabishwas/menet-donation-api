<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutSessionController extends Controller
{
    public function create() {
        return response()->json([
            "data" => "Hello World!"
        ]);
    }
}
