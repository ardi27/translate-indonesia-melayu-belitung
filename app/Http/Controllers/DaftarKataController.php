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
    public function update(Request $request,$id){
        $daftarKata=Kata_Dasar::where('id_katadasar',$id)->first();
        $daftarKata->update($request->all());
        $daftarKata->save();
        return redirect()->back()->with('success','Berhasil diupdate');
    }
}
