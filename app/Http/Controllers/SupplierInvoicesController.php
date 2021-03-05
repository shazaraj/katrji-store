<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientBill;
use App\Models\ClientInvoices;
use App\Models\Materials;
use App\Models\Store;
use App\Models\SupplierBill;
use App\Models\SupplierInvoices;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class SupplierInvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = SupplierInvoices::latest()->get();

            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('supplier',function($row){
                    return Suppliers::find($row->supplier_id)->name;
                })
//                ->addColumn('date',function($row){
//                    return Materials::find($row->material_id)->name;
//                })

                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-eye"></i> </a>';

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                    return $btn;

                })

                ->rawColumns(['action','supplier'])

                ->make(true);

            return;
        }

        $supplier = Suppliers::all();
        $material_id = Materials::all();
        return view("supplier_invoices.index" ,compact(['supplier','material_id']));

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
        $supplier_bill = SupplierInvoices::create(
            [
                'supplier_id' => $request->supplier_id,
//                'amount' => $request->amount,
                'all_price' => $request->all_price,
                'paid' => $request->paid,
                'discount' => $request->discount,
                'gain' => $request->gain,
                'remain' => $request->all_price -$request->discount - $request->paid,

//                'date' => $request->date,
            ])->id;
        for($i =0 ;$i<count($request->raws_id) ;$i++) {
            SupplierBill::create([
                "bill_id"=>$supplier_bill,
                "material_id"=>$request->raws_id[$i],
                "single_price"=>$request->raws_price[$i]/$request->raws_amount[$i],
                "amount"=>$request->raws_amount[$i],
                "expiry"=>$request->raws_exp[$i],
                "all_price"=>$request->raws_price[$i],
            ]);
        }
        // update store table
        for($i =0 ;$i<count($request->raws_id) ;$i++){
            $store = Store::where('material_id','=',$request->raws_id[$i])->count();
            if($store > 0 ){
                if ('expiry'!= $request->raws_exp[$i]){
//                    $sale_store =Store::where('material_id','=',$request->raws_id[$i])->get()->first();
                    $name1 = Materials::where('id','=',$request->raws_id[$i])->get();
                    $name = $name1->first()->name;
//                    $id = $material_id->first()->id;
                    $file_name = $name.$request->raws_exp[$i] ;
//                    $sale_store->material_id =$file_name;
                   $material =  Materials::create(
                        [
                            'name' => $file_name,
                            'amount' =>$request->raws_amount[$i],
                            'gain' => $request->gain,
                            'supplier_id' => $request->supplier_id,
                            'total_price' =>$request->raws_price[$i]/$request->raws_amount[$i],
                            'expiry' =>$request->raws_exp[$i],


                        ])->id;

                   Store::create(
                       [
                           'material_id' => $material,
                           'amount' =>$request->raws_amount[$i],
                           'total_price' =>$request->raws_price[$i]/$request->raws_amount[$i],
                           'expiry' =>$request->raws_exp[$i],
                       ]);
                }else{

                $sale_store =Store::where('material_id','=',$request->raws_id[$i])->get()->first();
                $sale_store->amount+=$request->raws_amount[$i];
                $sale_store-> total_price = $request->raws_price[$i];
                $sale_store-> expiry = $request->raws_exp[$i];
                $sale_store->save();
                }

            }else{
                Store::updateOrCreate(['material_id' => $request->raws_id[$i]],

                    [
                        'material_id' => $request->raws_id[$i],
                        'amount' =>$request->raws_amount[$i],
                        'total_price' =>$request->raws_price[$i],
                        'expiry' =>$request->raws_exp[$i],


                    ]);
            }

        }


        return response()->json(['status' =>200,'success' => ' تمت الإضافة بنجاح    .']);

    }

    public function getbills($id){
        $invoice = SupplierInvoices::with('material')->find($id);
        $bill = SupplierBill::where('id','=',$invoice->bill_id)->get();
        $supplier = Suppliers::find($invoice->supplier_id)->name;
        $row = [];
        if(!empty($invoice->material) && count($invoice->material) > 0)

            foreach ($invoice->material as $item){
                $mt =   Materials::find($item->material_id);
                $item2 = [
                    "name"=>$mt->name,
                    "amount"=>$item->amount,
                    "price"=>$item->single_price,
                    "expiry"=>$item->expiry,
                ];
                $row[] = $item2;
            }
        return  response()->json(["invoice"=>$invoice , "row"=>$row, "supplier"=>$supplier]);
    }
//    public function printInvoices(Request $request , $id){
//        $invoice = SupplierInvoices::with('material')->find($id);
//        $bill = SupplierBill::where('id','=',$invoice->bill_id)->get();
//        $client = Suppliers::find($invoice->supplier_id)->name;
//
//
//        $row = "";
//
//        if(!empty($invoice->material) && count($invoice->material) > 0)
//
//            foreach ($invoice->material as $item){
//                $mt =   Materials::find($item->material_id);
//
//                $row .="<tr> ".
//
//                    "<td width=".'30%'.">".$mt->name."</td>".
//                    "<td width=".'30%'.">".$item->amount."</td>".
//                    "<td width=".'30%'.">".$item->single_price."</td>"
//                    ."</tr>";
//
//            }
//        $output = '<!DOCTYPE html>
//<html lang="en">
//<head>
//    <meta charset="utf-8">
//    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
//    <meta http-equiv="x-ua-compatible" content="ie=edge">
//    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
//
//            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
//
//
//            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
//
//        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
//</head>
//<body class="deep-purple-skin" >'.
//            '<div class="row text-center" dir="rtl">
//       <div class="col-md-6 pull-right" style="margin-top: 5px; color: #06221b;">
//                    <h2><b> مكتب الخدمات البيطرية </b></h2>
//                    <h3><b> الدكتور حسن قاطرجي </b></h3>
//                    <h3> إشراف، معالجة وأدوية </h3>
//                    <h4> 963-933709555+ </h4>
//
//
//        </div>
//
//        <div class="row icon-bar-chart" dir="ltr">
//                       <div class="col-md-6 pull-left">
//                        <img class="badge-circle" src="http://localhost/laravel/katrji/public/app-assets/img/ico/logo.png" width="200" height="200" style="margin-left: 10px;">
//                        </div>
//        </div>
//    </div>
//<br>
//
//<div class="row" style="margin: 15px;">
//        <div class="col-md-12 pull-right text-center" style="margin-top: 25px;">
//            <div class="col-md-6 pull-right">
//				<p>
//					السيد المحترم:
//				</p>
//				<p>
//				'.$client.'
//				</p>
//			</div>
//			<div class="col-md-6 pull-left">
//				<p>
//					التاريخ
//				</p>
//				<p>
//				'.$invoice->created_at.'
//				</p>
//			</div>
//		</div>
//
//        <div class="row">
//            <div class="col-md-12 pull-right " dir="rtl" style="margin-top: 25px;">
//			<div class="col-md-6 pull-right">
//				<p>
//					إجمالي الفواتير
//				</p>
//				<p>
//				'.$invoice->all_price.'
//				</p>
//			</div>
//			<div class="col-md-6 pull-right">
//				<p>
//					المبلغ المدفوع
//				</p>
//				<p>
//					'.$invoice->paid.'
//				</p>
//			</div>
//			<div class="col-md-6 pull-right">
//				<p>
//					المبلغ المتبقي
//				</p>
//				<p>
//					'.$invoice->remain.'
//				</p>
//			</div>
//			<div class="col-md-6 pull-right">
//				<p>
//					حسم من قبلنا
//				</p>
//				<p>
//					'.$invoice->discount.'
//				</p>
//			</div>
//            </div>
//        </div>
//        <div class="col--md-2" style="margin-top: 25px;"></div>
//        <div class="col--md-8">
//            <table class="table table-striped" dir="rtl">
//                <thead>
//                 <tr>
//
//                <th style="text-align: center;">المادة</th>
//                <th style="text-align: center;">الكمية</th>
//                <th style="text-align: center;">المبلغ</th>
//
//
//                </tr>
//                </thead>
//                <tbody style="text-align: center;">
//                        '.$row.'
//                </tbody>
//
//            </table>
//        </div>
//        <div class="col--md-2"></div>
//        <div class="col-md-6" style="text-align: right; margin-top: 25px;">
//        <p>
//        :الملاحظات
//</p>
//</div>
//
//
//   </div>
//
//</body>
//</html>
//
//
//';
//        return $output;
//    }

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
        $item = SupplierInvoices::find($id);

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
        SupplierInvoices::find($id)->delete();


        return response()->json(['success'=>' تم الحذف بنجاح']);
    }
}
