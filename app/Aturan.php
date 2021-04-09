<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    //
    protected $table = "aturan_morf";
    public $timestamps=false;
    protected $fillable=['aturan_belitung','aturan_indo'];
    protected $primaryKey='id_aturan';
}
