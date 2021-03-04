<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory;
    protected $table="materials";

    protected $fillable = ["name", "supplier_id","expiry","gain","amount","total_price",];
    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];
    public function supplier(){
        return $this->belongsTo("App\Models\Suppliers",'supplier_id','id');
    }
}
