<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoices extends Model
{
    use HasFactory;
    protected $table="supplier_invoices";

    protected $fillable = ["supplier_id","all_price","paid","remain","discount","gain"];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    public function material(){
        return $this->hasMany("App\Models\SupplierBill",'bill_id','id');
    }
    public function supplier(){
        return $this->belongsTo("App\Models\Suppliers",'client_id','id');
    }
}
