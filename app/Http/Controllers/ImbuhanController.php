<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aturan;

class ImbuhanController extends Controller
{
    //
    public function index(){
        $imbuhan=Aturan::orderBy('aturan_belitung','ASC')->get();
        return view('imbuhan',['imbuhan'=>$imbuhan]);
    }
}
