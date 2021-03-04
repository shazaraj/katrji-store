<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBill extends Model
{
    use HasFactory;

    protected $table = "client_bill";

    protected $fillable = ["bill_id", "material_id", "single_price", "amount", "all_price",];

//    public function material(){
//
//        return $this->hasMany("App\Models\ClientInvoices",'material_id','id');
//
//    }
}
