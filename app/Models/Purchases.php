<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;
    protected $table="purchases";

    protected $fillable = [ "material_id","amount","price","date",];

    public function material(){
        return $this->belongsTo("App\Models\Materials",'material_id','id');
    }
}
