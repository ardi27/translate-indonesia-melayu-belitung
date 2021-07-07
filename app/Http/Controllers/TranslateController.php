<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kata_Dasar;
use App\Aturan;
use App\Frasa;
use App\Konfigurasi;

class TranslateController extends Controller
{
    //
    public function index()
    {
        return view('translate');
    }

    public function Proses(Request $request)
    {
        //proses tokenisasi
        $konfigurasi = Konfigurasi::where('id', '=', '0')->first();
        // echo '<b>Kalimat Melayu Riau</b>'."<pre>".print_r($request->kata,true)."</pre>";
        $kalimat = strtolower($request->kata);

        // echo '<b>Proses Case Folding</b>' . "<pre>" . print_r($kalimat, true) . "</pre>";
        $token = explode(" ", $this->simbol($kalimat));

        // echo '<b>proses token</b>' . "<pre>" . print_r($token, true) . "</pre>";
        //hapus spasi setelah enter
        // for ($j = 0; $j < count($token); $j++) {
        //     if ($token[$j] == "") {
        //         array_splice($token, $j, 1);
        //     }
        // }
        //frase
        // return response()->json([
        //     'title' => 'Proses Tokenisasi',
        //     'result' => $token
        // ]);
        $frasa = Frasa::all();
        $kondisi = [];
        for ($i = 0; $i < count($token); $i++) {
            $cek = 0;
            if ($i == count($token) - 1) {
                $kondisi[$i] = false;
                break;
            }
            foreach ($frasa as $f) {
                $temp = explode(" ", $f->belitung);
                if ($temp[0] == $token[$i]) {
                    if ($temp[1] == $token[$i + 1]) {
                        $token[$i] = $f->indo;
                        array_splice($token, $i + 1, 1);
                        $cek = 1;
                        // echo '<b>Proses Frasa</b>' . "<pre>" . '<b>kata asal = </b>' . print_r($temp[0] . ' ' . $temp[1], true) . '<br><b>hasil = </b>' . print_r($token[$i], true) . "<pre>";
                    }
                }
            }
            if ($cek == 0) {
                $kondisi[$i] = false;
            } else {
                $kondisi[$i] = true;
            }
        }

        $output = array();


        if ($token[0] > "") {
            $j = 0;
            foreach ($token as  $value) {

                $basicWords = array();
                $data = Kata_Dasar::all();

                foreach ($data as $row) {
                    $basicWords[$row->kdNama] = $row->kdArti;
                }
                if ($kondisi[$j] == null || $kondisi[$j] == false) {
                    $kata = $this->stem($value);
                } else {
                    $kata['awalan'] = "";
                    $kata['akhiran'] = "";
                    $kata['arti'] = $value;

                    // echo "<pre>".'<b>Hasil = </b>'.print_r($kata['arti'],true)."</pre>";
                }
                if ($kata['awalan'] == '') {
                    if (preg_match("/[\,\?\.\:\!]/", $kata['arti'])) {
                        $output[count($output) - 1] = substr($output[count($output) - 1], 0, -1);
                        $output[] = $kata['arti'] . $kata['akhiran'] . " ";
                    } elseif (preg_match("/[\-\"\:]/", $kata['arti'])) {
                        $output[count($output) - 1] = substr($output[count($output) - 1], 0, -1);
                        $output[] = $kata['arti'] . $kata['akhiran'];
                    } else {
                        if ($kata['is_basicword'] == false && $konfigurasi->leven == '1') {
                            $leven = [];
                            foreach ($data as $row) {
                                $cekKata = [];
                                for ($i = 0; $i < strlen($kata['arti']); $i++) {
                                    array_push($cekKata, $kata['arti'][$i]);
                                }

                                // // array_splice($cekKata, 0, 0, "*");
                                // // array_push($cekKata, "*");
                                // $cekKata = implode("", $cekKata);
                                // // $cekKata = substr($cekKata, 1,);
                                // dd($cekKata);
                                if ($kata['arti'] != "") {
                                    if (levenshtein($kata['arti'], $row->katadasar) < 3) {
                                        $cek = 1;
                                        foreach ($cekKata as $char) {
                                            if (strpos($row->katadasar, $char) === false) {
                                                $cek = 0;
                                            }
                                        }

                                        if ($cek == 1) {
                                            array_push($leven, $row->arti_kata);
                                        }
                                    }
                                }
                            }
                            // dd($leven);
                            if (count($leven) > 0) {
                                $kata['arti'] = $leven[0];
                            }
                            // echo '<b>proses Leven</b>' . "<pre>" . print_r($leven, true) . "</pre>";
                        }
                        $output[] = $kata['arti'] . $kata['akhiran'] . " ";
                    }
                } else if ($kata['akhiran'] == '') {
                    $output[] = $kata['awalan'] . $kata['arti'] . " ";
                } else if ($kata['awalan'] == '' && $kata['akhiran'] == '') {
                    $output[] = $kata['arti'] . " ";
                } else {
                    $output[] = $kata['awalan'] . $kata['arti'] . $kata['akhiran'] . " ";
                    //$output = $kata;
                }
                $j++;
                // echo '<b>Proses Stemming</b>' . "<pre>" . print_r($kata, true) . "</pre>";
            }
        } else {
            $output[] = "Terjemahan Bahasa Indonesia";
        }
        // echo '<b>Proses Terjemahan</b>' . "<pre>" . print_r(implode(' ', $output), true) . "</pre>";
        // return response()->json([
        //     'title' => 'Proses Terjemahan',
        //     'result' => implode('', $output)
        // ]);
        return response()->json($output);
    }

    function simbol($data)
    {
        $pre = preg_replace("/[^a-zA-Z\s\,\?\.\-\!\n\:\"]/", "", $data);
        $pre1 = str_replace('?', ' ?', $pre);
        $pre2 = str_replace('.', ' .', $pre1);
        $pre3 = str_replace(',', ' ,', $pre2);
        $pre4 = str_replace('!', ' !', $pre3);
        $pre5 = str_replace('"', ' " ', $pre4);
        $pre6 = str_replace('-', ' - ', $pre5);
        $pre7 = str_replace(':', ' :', $pre6);
        $pre8 = str_replace(array("\n", "\n"), " \n ", $pre7);
        return $pre8;
    }

    function cekKamus($kata)
    {
        global $basicWords;

        $data = Kata_Dasar::all();
        foreach ($data as $row) {
            $basicWords[$row->katadasar] = $row->arti_kata;
        }
        return isset($basicWords[$kata]) ? true : false;
    }

    function aturan($data)
    {
        $aturan = Aturan::all();
        $rule = '';
        foreach ($aturan as $row) {
            if ($data == $row->aturan_belitung) {
                $rule = $row->aturan_indo;
                return $rule;
            }
        }
        return $rule;
    }

    function stem($kata)
    {
        global $rules;
        global $basicWords;
        global $suffix;
        global $prefix;

        $data = Kata_Dasar::all();
        foreach ($data as $row) {
            $basicWords[$row->kdNama] = $row->kdArti;
        }

        $rules = array();
        $prefix = array();
        $suffix = '';
        $kataAsal = strtolower($kata);

        if ($this->cekKamus($kata)) {
            return array('awalan' => '', 'akhiran' => '', 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => $basicWords[$kata]);
        }
        /* 1. $this->Akhiran */
        $kata = $this->Akhiran($kata);


        /* 2. $this->awalan */
        $kata = $this->awalan($kata);




        if ($this->cekKamus($kata)) {
            // Jika ada kembalikan
            if (count($prefix) > 1) {
                if ($prefix[0] == 'ke' && $prefix[1] == 'ber') {
                    $temp = $prefix[0];
                    $prefix[0] = $prefix[1];
                    $prefix[1] = $temp;
                } else if ($prefix[0] == 'per' && $prefix[1] == 'mem') {
                    $temp = $prefix[0];
                    $prefix[0] = $prefix[1];
                    $prefix[1] = $temp;
                } else if ($prefix[0] == 'ke' && $prefix[1] == 'be') {
                    $temp = $prefix[0];
                    $prefix[0] = $prefix[1];
                    $prefix[1] = $temp;
                }
            } elseif (strpos($suffix, "-")) {
                $suffix = explode("-", $suffix);
                if (count($suffix) > 1) {
                    if ($suffix[0] == 'nya' && $suffix[1] == 'kan') {
                        $temp = $suffix[0];
                        $suffix[0] = $suffix[1];
                        $suffix[1] = $temp;
                        $suffix = implode("", $suffix);
                    } else if ($suffix[0] == 'lah' && $suffix[1] == 'kan') {
                        $temp = $suffix[0];
                        $suffix[0] = $suffix[1];
                        $suffix[1] = $temp;
                        $suffix = implode("", $suffix);
                    } else if ($suffix[0] == 'nya' && $suffix[1] == 'an') {
                        $temp = $suffix[0];
                        $suffix[0] = $suffix[1];
                        $suffix[1] = $temp;
                        $suffix = implode("", $suffix);
                    }
                }
            }

            $prefix = implode('', $prefix);
            if ((strpos($prefix, 'pen') == 2 || $prefix == 'pen') && $basicWords[$kata][0] == 't') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            } else if ($prefix == "meng" && $basicWords[$kata][0] == 'k') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            } else if ($prefix == "men" && $basicWords[$kata][0] == 't') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            } else if ($prefix == "meny" && $basicWords[$kata][0] == 's') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            }
            // else if ($prefix == "mem" && $basicWords[$kata][0] == 'p') {
            //     # code...
            //     return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            // } 
            else if ($prefix == "pem" && $basicWords[$kata][0] == 'p') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            } else if ($prefix == "peng" && $basicWords[$kata][0] == 'k') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            } else if ($prefix == "peny" && $basicWords[$kata][0] == 's') {
                # code...
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => substr($basicWords[$kata], 1));
            } else {
                return array('awalan' => $prefix, 'akhiran' => $suffix, 'kata' => $kataAsal, 'result' => $kata, 'rules' => $rules, 'is_basicword' => true, 'arti' => $basicWords[$kata]);
            } //return array('input'=>$kataAsal,'result'=>$kata, 'is_basicword'=>true);
        } else {

            // Jika tidak ada kembalikan "" atau $kataAsal sesuai kebutuhan
            return array('awalan' => '', 'akhiran' => '', 'result' => $kataAsal, 'arti' => $kataAsal, 'rules' => $rules, 'is_basicword' => false);
            //return array('input'=>$kataAsal,'result'=>$kata, 'is_basicword'=>false);
        }
    }

    function Akhiran($kata)
    {
        global $rules;
        global $prefix;
        global $suffix;

        $kataAsal = $kata;
        if (preg_match('/(an|eq|kan|e)$/i', $kata)) { // Cek Inflection Suffixes sementara lah problem contoh mengalah

            $kata__ = preg_replace('/(an|eq|kan|e)$/i', '', $kata);
            if ($this->cekKamus($kata__)) {
                $akhir = str_replace($kata__, '', $kata);
                $suffix = $this->aturan($akhir);
                $rules[] = 'an|eq|kan|e -> hapus';
                return $kata__; // Jika ada balik
            } else {
                $__kata = $this->awalan($kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata;
                }
                $kata__ = preg_replace('/(an|eq|kan|e)$/i', '', $__kata);
                $akhir = str_replace($kata__, '', $__kata);
                $suffix = $this->aturan($akhir);
                $rules[] = 'an|eq|kan|e -> hapus';
                return $kata__;
            }
        }
        return $kataAsal;
    }


    function awalan($kata)
    {
        global $rules;
        global $prefix;
        $kataAsal = $kata;


        if ($this->cekKamus($kata)) {
            return $kata;
        }

        if (preg_match('/^(de|[ks]e)/i', $kata)) { // Jika di-,ke-,se-
            $__kata = preg_replace('/^(de|[ks]e)/i', '', $kata);

            if ($this->cekKamus($__kata)) {
                $awal = str_replace($__kata, '', $kata);
                $prefix[] = $this->aturan($awal);
                $rules[] = 'de|ke|se -> hapus';
                return $__kata; // Jika ada balik
            }
            //$rules[] = '^di|ke|se -> hapus';
            $__kata__ = $this->awalan($__kata);

            if ($this->cekKamus($__kata__)) {
                $awal = str_replace($__kata, '', $__kata);
                $prefix[] = $this->aturan($awal);
                return $__kata__;
            }
        }
        if (preg_match('/^(nge)\S{1,}/', $kata)) {                         // Jika awalan “nge-”
            if (preg_match('/^(nge)[mlrwy]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(nge)/', '', $kata);

                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'nge -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'nge -> hapus';
                    return $__kata__;
                }
            }


            if (preg_match('/^(ngen)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ngen)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ngen -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ngen -> hapus';
                    return $__kata__;
                }
            }


            if (preg_match('/^(ngem)[b]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ngem)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ngem -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ngem -> hapus';
                    return $__kata__;
                }
            }
            if (preg_match('/^(ngeng)[g]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ngeng)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ngeng -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ngeng -> hapus';
                    return $__kata__;
                }
            }
        }
        if (preg_match('/^(ng)\S{1,}/', $kata)) {
            if (preg_match('/^(ng)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ng)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ng -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(ng)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ng)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ng -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        // END AWALAN NG
        //================================================= awalan BE =================================================//	
        if (preg_match('/^(be)\S{1,}/', $kata)) {

            if (preg_match('/^(be)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(be)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'be -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ber -> hapus';
                    return $__kata__;
                }
            }

            if (preg_match('/^(ber)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ber)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $rules[] = 'ber -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //End Awalan BE
        //================================================= awalan DE =================================================//	
        if (preg_match('/^(de)\S{1,}/', $kata)) {

            if (preg_match('/^(de)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(de)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'de -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //End AWALAN BE

        //================================================= $this->awalan PE =================================================//	
        if (preg_match('/^(pe)\S{1,}/', $kata)) {

            if (preg_match('/^(peng)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(peng)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $rules[] = 'peng -> hapus';
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(peng)[u]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(peng)/', 'k', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'peng -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(peng)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(peng)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'peng -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(pem)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(pem)/', 'p', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'pem -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(pem)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(pem)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'pem -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(pe)[lmrswtkn]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(pe)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'pe -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(pen)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(pen)/', 't', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'pen -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(pen)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(pen)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'pen -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }

            if (preg_match('/^(peny)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(peny)/', 's', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'peny -> hapus';
                    return $__kata;
                }
                $__kata__ = $this->awalan($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //end awalan PE

        //================================================= awalan SE =================================================//
        if (preg_match('/^(se)\S{1,}/', $kata)) {

            if (preg_match('/^(se)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(se)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'se -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //ENd awalan Se
        //=================================================awalan TE =================================================//

        if (preg_match('/^(te)\S{1,}/', $kata)) {

            if (preg_match('/^(te)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(te)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'te -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
            if (preg_match('/^(ter)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ter)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ter -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //=================================================END awalan TE =================================================//
        //================================================= awalan KU =================================================//
        if (preg_match('/^(ku)\S{1,}/', $kata)) {

            if (preg_match('/^(ku)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ku)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ku -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //=================================================end awalan KU =================================================//
        //================================================= awalan NY =================================================//
        if (preg_match('/^(ny)\S{1,}/', $kata)) {

            if (preg_match('/^(ny)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ny)/', 'c', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ny -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
            if (preg_match('/^(ny)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(ny)/', 's', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'ny -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        if (preg_match('/^(m)\S{1,}/', $kata)) {

            if (preg_match('/^(m)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(m)/', 'p', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'm -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //================================================= $this->awalan N- =================================================//
        if (preg_match('/^(n)\S{1,}/', $kata)) {

            if (preg_match('/^(n)\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(n)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'n -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
            if (preg_match('/^(n)[aiueo]\S{1,}/', $kata)) {
                $__kata = preg_replace('/^(n)/', 't', $kata);
                if ($this->cekKamus($__kata)) {
                    $awal = str_replace($__kata, '', $kata);
                    $prefix[] = $this->aturan($awal);
                    $rules[] = 'n -> hapus';
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->awalan($__kata);
                //$__kata__ = Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    $awal = str_replace($__kata__, '', $__kata);
                    $prefix[] = $this->aturan($awal);
                    return $__kata__;
                }
            }
        }
        //=================================================end awalan NY =================================================//

        return $kataAsal;
    }
}
