<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientBill;
use App\Models\ClientInvoices;
use App\Models\Materials;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;


class BillController extends Controller

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
{
   public function index(Request $request)
{
    if ($request->ajax()) {
        $data = ClientInvoices::where('type','=',1)->get();

//            $data = ClientInvoices::latest()->get();

        return Datatables::of($data)

            ->addIndexColumn()
            ->addColumn('client',function($row){
                return Client::find($row->client_id)->name;
            })
//                ->addColumn('date',function($row){
//                    return Materials::find($row->material_id)->name;
//                })

            ->addColumn('action', function($row){

//                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-eye"></i> </a>';

                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                return $btn;

            })

            ->rawColumns(['action','client'])

            ->make(true);

        return;
    }

    $client = Client::all();
    $material_id = Materials::all();
    return view("bills.showBills" ,compact(['client','material_id']));
}

public function index1(Request $request){
    if ($request->ajax()) {
        $data = ClientInvoices::where('type','=',1)->get();

//            $data = ClientInvoices::latest()->get();

        return Datatables::of($data)

            ->addIndexColumn()
            ->addColumn('client',function($row){
                return Client::find($row->client_id)->name;
            })
//                ->addColumn('date',function($row){
//                    return Materials::find($row->material_id)->name;
//                })

            ->addColumn('action', function($row){

//                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-eye"></i> </a>';

                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                return $btn;

            })

            ->rawColumns(['action','client'])

            ->make(true);

        return;
    }

    $client = Client::all();
    $material_id = Materials::all();
    return view("bills.index" ,compact(['client','material_id']));
}
    public function filter(Request $request , $id){

       $remain = DB::table('client_invoices')
           ->where('client_id','=',$id)
           ->sum('remain');
        $all_price = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('all_price');
        $paid = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('paid');
        $discount = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('discount');
        return  response()->json(["paid"=>$paid , "remain"=>$remain,"all_price"=>$all_price , "discount"=>$discount]);


    }


    public function get(Request $request , $id){

       $sd = DB::table('client_invoices')
            ->where('client_id','=',$id)
//            ->where('type','=',1)
            ->get();
        if(!empty($sd) && count($sd) > 0){
        foreach ($sd as $item){
            $item2 = [
                "paid"=>$item->paid,
                "created_at"=>$item->created_at,
            ];
            $row[] = $item2;
        }
        }
        return  response()->json(["row"=>$row]);


    }

    /**
    print client invoice bills
     */
    public function printBill($id){

        $date = Carbon::now();

        $discount = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('discount');
        $remain = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('remain');
        $paid1 = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('paid');

        $all_price = DB::table('client_invoices')
            ->where('client_id','=',$id)
            ->sum('all_price');

        $sd = DB::table('client_invoices')
            ->where('client_id','=',$id)
//            ->where('type','=',1)
            ->get();
        $client = Client::find($sd->client_id)->name;

//        $img = asset('app-assets/img/ico/logo.png');

        $row = "";
        if(!empty($sd) && count($sd) > 0){
            foreach ($sd as $item){

                $row .= "<tr> ".

                "<td>".$item->paid."</td>".
                "<td>".$item->created_at."</td>"
                ."</tr>";
            }
        }

        $output = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body class="deep-purple-skin">'.
            '<div class="row text-center" dir="rtl">
                <div class="col-md-6 pull-right" style="margin-top: 5px; color: #06221b;">
                    <h2><b> مكتب الخدمات البيطرية </b></h2>
                    <h3><b> الدكتور حسن قاطرجي </b></h3>
                    <h3> إشراف، معالجة وأدوية </h3>
                    <h4> 963-933709555+ </h4>


                </div>

        </div>
        <div class="row icon-bar-chart" dir="ltr">
                       <div class="col-md-6 pull-left">
                        <img class="badge-circle" src="' .asset('app-assets/img/ico/logo.png').'" width="200" height="200" style="margin-left: 15px;">

                        </div>
        </div>
<hr>
<div class="row">
<div class="col-md-12 pull-right text-right">
<div class="col-md-6 pull-right">
				<p>
					اسم الزبون:
				</p>
				<p>
				'.$client.'
				</p>
			</div>
			<div class="col-md-6 pull-left">
				<p>
					التاريخ
				</p>
				<p>
				'.$date.'
				</p>
			</div>
<table class="table table-striped" dir="rtl">
<tr>

<th>الدفعات</th>
<th>التاريخ</th>


</tr>
'.$row.'
</table>


							</div>
</div>
   <div class="row">
		<div class="col-md-6 pull-right">
		</div>
			<div class="col-md-6 pull-right">
				<p>
					إجمالي الفواتير
				</p>
				<p>
				'.$all_price.'
				</p>
			</div>
			<div class="col-md-6 pull-right">
				<p>
					المبلغ المدفوع
				</p>
				<p>
					'.$paid1.'
				</p>
			</div>
			<div class="col-md-6 pull-right">
				<p>
					المبلغ المتبقي
				</p>
				<p>
					'.$remain.'
				</p>
			</div>
			<div class="col-md-6 pull-right">
				<p>
					مبلغ الحسم
				</p>
				<p>
					'.$discount.'
				</p>
			</div>

   </div>

</div>


';
        return $output;
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
        ClientInvoices::create(
            [
                'client_id'=>$request->client_id,
                'all_price'=>0,
                'remain'=>-$request->paid,
                'discount'=>0,
                'paid' => $request->paid,
                'type'=>1
            ]);
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
        //
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
        //
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
