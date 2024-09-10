<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use Illuminate\Http\JsonResponse;

class DebugController extends Controller
{
    public function index() : JsonResponse
    {
        $uuid = Helpers::generateUuid();
        return response()->json($uuid);
    }
}
