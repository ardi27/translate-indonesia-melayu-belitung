<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aturan;
use Illuminate\Support\Facades\Validator;

class ImbuhanController extends Controller
{
    //
    public function index()
    {
        $imbuhan = Aturan::orderBy('aturan_belitung', 'ASC')->get();
        return view('imbuhan', ['imbuhan' => $imbuhan]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aturan_belitung' => 'required',
            'aturan_indo' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        Aturan::create($request->all());
        return redirect()->back()->with('success', 'Berhasil ditambah');
    }
    public function update(Request $request, $id)
    {
        $daftarKata = Aturan::where('id_katadasar', $id)->first();
        $daftarKata->update($request->all());
        $daftarKata->save();
        return redirect()->back()->with('success', 'Berhasil diupdate');
    }
}
