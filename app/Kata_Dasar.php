<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kata_Dasar extends Model
{
    //
    protected $table = "tabel_katadasar";
    public $timestamps=false;
    protected $fillable=['katadasar','arti_kata'];
    protected $primaryKey='id_katadasar';
}
