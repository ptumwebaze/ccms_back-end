<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class DashboardController extends Controller
{
 public function index(){
   $arr['totalcomplaints'] = Complaint::where('state',1)->count('id');
   $arr['escalated_comp'] =  Complaint::where('state',1)->where('status','Escalated')->count('id');
   $arr['resolved_comp'] =  Complaint::where('state',1)->where('status','Resolved')->count('id');
   $cmonth = date('m');
   $cyear = date('Y');
   $arr['compmonth'] = Complaint::whereMonth('created_at',$cmonth )->whereYear('created_at',$cyear)->count('id');

   return $arr;
 }

}
