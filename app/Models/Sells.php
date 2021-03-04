<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sells extends Model
{
    use HasFactory;
    protected $table="sells";

    protected $fillable = [ "material_id","amount","all_price","date",];

    public function material(){
        return $this->belongsTo("App\Models\Materials",'material_id','id');
    }

}
