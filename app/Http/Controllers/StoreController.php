<?php

namespace App\Http\Controllers;

use App\Models\Materials;
use App\Models\Store;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Carbon;
use Validator;

class StoreController extends Controller
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

            $data = Store::latest()->get();

            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('materials',function($row){
                    return Materials::find($row->material_id)->name;
                })

                ->addColumn('action', function($row){

//                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-edit"></i> </a>';

                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                    return $btn;

                })

                ->rawColumns(['action','materials'])

                ->make(true);

            return;
        }

        $materials = Materials::all();

        return view("store.index",compact('materials'));

    }
    public function notify(){

        $stor_exp = Store::where('expiry', '<', Carbon::now()

            ->addDays(45)->toDateTimeString())

            ->where('expiry', '>', Carbon::now())->get();
        return view("notify.index",compact("stor_exp"));
    }

    public function notify1(){

        $stor_exp = Store::where('expiry', '<', Carbon::now()

            ->addDays(45)->toDateTimeString())

            ->where('expiry', '>', Carbon::now())->get();
        $row = "";
        if(!empty($stor_exp) && count($stor_exp) > 0)

            foreach ($stor_exp as $item){
                $mt =   Materials::find($item->material_id);

                $row .="<tr> ".

                    "<td>".$mt->name."</td>".
                    "<td>".$item->amount."</td>".
                    "<td>".$item->expiry."</td>"
                    ."</tr>";

            }
        return  response()->json(["row"=>$row]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Store::find($id)->delete();


        return response()->json(['success'=>' تم الحذف بنجاح']);
    }
}
