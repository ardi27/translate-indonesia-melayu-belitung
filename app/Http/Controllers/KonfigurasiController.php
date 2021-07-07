<?php

namespace App\Http\Controllers;

use App\Konfigurasi;
use Illuminate\Http\Request;

class KonfigurasiController extends Controller
{
    //
    public function index()
    {
        $konfigursi = Konfigurasi::all();
        return view('konfigurasi', [
            'konfigurasi' => $konfigursi
        ]);
    }
    public function setKonfigurasi(Request $request, $id)
    {
        $konfigursi = Konfigurasi::where('id', '=', $id)->first();
        $konfigursi->leven = $request->leven ?? '0';
        $konfigursi->save();
        return redirect()->back()->with('success', 'Konfigurasi berhasil diubah');
    }
}
