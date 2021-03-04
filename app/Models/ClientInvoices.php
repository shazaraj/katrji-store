<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoices extends Model
{
    use HasFactory;
    protected $table="client_invoices";

    protected $fillable = ["client_id","all_price","paid","remain","discount","type"];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];
    public function material(){
        return $this->hasMany("App\Models\ClientBill",'bill_id','id');
    }
    public function client(){
        return $this->belongsTo("App\Models\Clients",'client_id','id');
    }
}
