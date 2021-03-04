<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodayReportController extends Controller
{
    //report
    public function getRepo(){
        return view("reports.index");
    }
    //day-report
    public function getReport(Request $request ,$day_repo = null){
        if ($request->day_repo != '') {

            $date = $request->day_repo;

            $sales = DB::table('client_invoices')
                ->whereDate('created_at','=',  $day_repo)
                ->sum('paid');
            $payments = DB::table('supplier_invoices')
                ->whereDate('created_at','=',$day_repo)
                ->sum('paid');
            $remain_clients= DB::table('client_invoices')
                ->whereDate('created_at','=',$day_repo)
                ->sum('remain');
            $remain_suppliers= DB::table('supplier_invoices')
                ->whereDate('created_at','=',$day_repo)
                ->sum('remain');
            $medicines= DB::table('store')
                ->sum('amount');

            $remain = $sales - $payments ;

        }
//        return  $sales;
        return view("reports.dayRepo",compact(["date","sales","payments" ,"remain_clients","remain_suppliers" ,"medicines","remain"]));
    }
  // get view {{day}} only
    public function dayReport(Request $request){
        return view("reports.dayRepo");
    }
    //month-report
    public function getMonth(Request $request , $from_date = null, $to_date = null){
        if ($request->from_date != '' && $request->to_date != '') {
            $from = $request->from_date;
            $to = $request->to_date;
            $sales = DB::table('client_invoices')
                ->whereBetween('created_at', array($request->from_date, $request->to_date))
                ->sum('paid');
            $payments = DB::table('supplier_invoices')
                ->whereBetween('created_at', array($request->from_date, $request->to_date))
                ->sum('paid');
            $remain_client= DB::table('client_invoices')
                ->whereBetween('created_at', array($request->from_date, $request->to_date))
                ->sum('remain');
            $remain_suppliers= DB::table('supplier_invoices')
                ->whereBetween('created_at', array($request->from_date, $request->to_date))
                ->sum('remain');
            $medicines= DB::table('store')
                ->sum('amount');

            $remain = $sales - $payments ;

        }

        return view("reports.monthRepo", compact(["from","to","sales","payments"  ,"medicines","remain","remain_suppliers","remain_client"]));

    }
    // get view {{month}} only
    public function monthReport(Request $request){
        return view("reports.monthRepo");
    }
}
