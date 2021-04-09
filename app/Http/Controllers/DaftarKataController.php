<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kata_Dasar;
use Illuminate\Support\Facades\Validator;

class DaftarKataController extends Controller
{
    //
    public function index(Request $request)
    {
        $daftarKata = Kata_Dasar::orderBy('katadasar', 'ASC')->get();
        return view('daftar_kata', ['daftarKata' => $daftarKata]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'katadasar' => 'required',
            'arti_kata' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        Kata_Dasar::create($request->all());
        return redirect()->back()->with('success', 'Berhasil ditambah');
    }
    public function update(Request $request, $id)
    {
        $daftarKata = Kata_Dasar::where('id_katadasar', $id)->first();
        $daftarKata->update($request->all());
        $daftarKata->save();
        return redirect()->back()->with('success', 'Berhasil diupdate');
    }
}
