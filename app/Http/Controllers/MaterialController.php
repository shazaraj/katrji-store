<?php

namespace App\Http\Controllers;

use App\Models\Materials;
use App\Models\Store;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use DataTables;
use Validator;


class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {

            $data = Materials::latest()->get();

            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('supplier_id',function($row){
                    return Suppliers::find($row->supplier_id)->name;
                })

                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-edit"></i> </a>';

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                    return $btn;

                })

                ->rawColumns(['action','client_id'])

                ->make(true);

            return;
        }

        $supplier_id =Suppliers::all();
        return view("materials.index",compact('supplier_id'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
//        Store::Create(['id' => $request->_id],
//
//            [
//                'material_id' => $request->id,
//                'expiry' => $request->expiry,
//                'amount' => $request->amount,
//                'total_price' => $request->total_price
//
//            ]);
//
//
//        return response()->json(['status' =>200,'success' => ' تمت الإضافة بنجاح.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateErrors = Validator::make($request->all(),
            [
                'name' => 'required|string|min:3|max:200',
                'expiry' => 'required',
                'supplier_id' => 'required',
                'total_price' => 'required'

            ]);
        if ($validateErrors->fails()) {
            return response()->json(['status' => 201, 'success' => $validateErrors->errors()->first()]);
        }

//        $name = $request->name;
//        if(Materials::where([['name','=',$name],['id','!=',$request->id]])->count()>0){
//            return response()->json(['status' => 201, 'success' => 'الاسم موجود مسبقا']);
//        }
        Materials::updateOrCreate(['id' => $request->_id],

            [
                'name' => $request->name,
                'expiry' => $request->expiry,
                'supplier_id' => $request->supplier_id,
                'gain' => $request->gain,
                'amount' => $request->amount,
                'total_price' => $request->total_price

            ]);

        $material_id = Materials::Where('name','=',$request->name);
        $id = $material_id->first()->id;

        Store::updateOrCreate(['material_id' => $request->_id],

            [
//                'material_id' => $id,
                'expiry' => $request->expiry,
                'amount' => $request->amount,
                'total_price' => $request->total_price

            ]);

        return response()->json(['status' =>200,'success' => ' تمت الإضافة بنجاح.']);

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
        $item = Materials::find($id);

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
        Materials::updateOrCreate(['id' => $id],

            [
                'name' => $request->get("name"),
                'expiry' => $request->get("expiry"),
                'supplier_id' => $request->get("supplier_id"),
                'gain' => $request->get("gain"),
                'amount' => $request->get("amount"),
                'total_price' => $request->get("total_price")

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
        Materials::find($id)->delete();


        return response()->json(['success'=>' تم الحذف بنجاح']);
    }
}
