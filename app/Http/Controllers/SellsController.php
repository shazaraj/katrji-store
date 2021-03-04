<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientInvoices;
use App\Models\Materials;
use App\Models\Sells;
use App\Models\Store;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class SellsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = ClientInvoices::latest()->get();

            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('clients',function($row){
                    return Client::find($row->client_id)->name;
                })
                ->addColumn('materials',function($row){
                    return Materials::find($row->material_id)->name;
                })

                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-edit"></i> </a>';

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                    return $btn;

                })

                ->rawColumns(['action','clients','materials'])

                ->make(true);

            return;
        }

        $client_invoices = ClientInvoices::all();
        $clients = Client::all();
        $materials = Materials::all();
        return view("sale.index" ,compact(['client_invoices','clients','materials']));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add to client invoices

        ClientInvoices::updateOrCreate(['id'=>$request->_id],
            [
                'client_id' => $request->client_id,
                'material_id' => $request->material_id,
                'amount' => $request->amount,
                'all_price' => $request->all_price,
                'paid' => $request->paid,
                'remain' => $request->remain,
                'date' => $request->date,
            ]);

        // update store table
        $store = Store::where('material_id','=',$request->material_id)->count();
        if($store > 0 ){

            $sale_store =Store::where('material_id','=',$request->material_id)->get()->first();
            $sale_store->amount-=$request->amount;
            $sale_store->save();
        }else{
            Store::updateOrCreate(['material_id' => $request->material_id],

                [
                    'material_id' => $request->material_id,
                    'amount' => $request->amount,


                ]);
        }


        return response()->json(['status' =>200,'success' => ' تمت الإضافة بنجاح    .']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Sells::find($id);

        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update sell table
        ClientInvoices::updateOrCreate(['id' => $id],

            [
                'client_id' => $request->get("client_id"),
                'material_id' => $request->get("material_id"),
                'amount' => $request->get("amount"),
                'all_price' => $request->get("all_price"),
                'paid' => $request->get("paid"),
                'remain' => $request->get("remain"),
                'date' => $request->get("date")

            ]);
        return response()->json(['success' => 'تم التعديل بنجاج']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClientInvoices::find($id)->delete();

        return response()->json(['success'=>' تم الحذف بنجاح']);
    }
}
