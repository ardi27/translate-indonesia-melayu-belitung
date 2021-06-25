<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Kata_Dasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    //
    public function index(Request $request)
    {
        $cache = Redis::get('hurufCount');
        if (!$cache) {
            $huruf = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
            $kataDasar = [];
            $output = [];
            for ($i = 0; $i < count($huruf); $i++) {
                $kataDasar[$i] = Kata_Dasar::orderBy('katadasar', 'ASC')->where('katadasar', 'like', $huruf[$i] . '%')->count();
                $output[$i] = [$huruf[$i] => $kataDasar[$i]];
            }
            $total = Kata_Dasar::count();
            Redis::set('hurufCount', json_encode(['message' => 'success', 'data' => $output, 'total' => $total]));
            return response()->json(['message' => 'success', 'data' => $output, 'total' => $total], 200);
        }
        return response()->json(json_decode($cache));
    }
    public function store(Request $request)
    {

        return response()->json($request->all(), 201);
    }
}
