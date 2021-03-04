<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientType;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class ClientController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {

            $data = Client::latest()->get();

            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('client_type',function($row){
                    return ClientType::find($row->client_type)->name;
                })

                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"> <i class="fa fa-edit"></i> </a>';

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"> <i class="fa fa-trash-o"></i> </a>';

                    return $btn;

                })

                ->rawColumns(['action','cliet_type'])

                ->make(true);

            return;
        }

        $client_type = ClientType::all();
        return view("client.index" ,compact('client_type'));

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
        $validateErrors = Validator::make($request->all(),
            [
                'name' => 'required|string|min:3|max:200',
                'mobile' => 'required|min:3|max:12',

            ]);
        if ($validateErrors->fails()) {
            return response()->json(['status' => 201, 'success' => $validateErrors->errors()->first()]);
        }
        Client::updateOrCreate(['id' => $request->_id],

            [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'client_type' => 1,
                'services_type' => $request->services_type

            ]);


        return response()->json(['status' =>200,'success' => ' تمت الإضافة بنجاح    .']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $item = Client::find($id);

        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Client::updateOrCreate(['id' => $id],

            [
                'name' => $request->get("name"),
                'mobile' => $request->get("mobile"),
                'client_type' => 1,
                'services_type' => $request->get("services_type")

            ]);


        return response()->json(['success' => 'تم التعديل بنجاج']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        Client::find($id)->delete();


        return response()->json(['success'=>' تم الحذف بنجاح']);
    }
}
