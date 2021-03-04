<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierBill extends Model
{
    use HasFactory;
    protected $table = "supplier_bill";

    protected $fillable = ["bill_id", "material_id", "single_price", "amount","expiry", "all_price",];

}
