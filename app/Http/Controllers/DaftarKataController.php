<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kata_Dasar;

class DaftarKataController extends Controller
{
    //
    public function index(){
        $daftarKata=Kata_Dasar::orderBy('katadasar','ASC')->get();
        return view('daftar_kata',['daftarKata'=>$daftarKata]);
    }
}
