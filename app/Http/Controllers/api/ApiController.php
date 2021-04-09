<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Kata_Dasar;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function index(Request $request)
    {
        $kataDasar = Kata_Dasar::orderBy('katadasar', 'ASC')->paginate(10);
        return response()->json(['message' => 'success', 'data' => $kataDasar], 200);
    }
    public function store(Request $request)
    {

        return response()->json($request->all(), 201);
    }
}
