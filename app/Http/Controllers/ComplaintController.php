<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Http\Resources\ComplaintResource;
use App\Models\Audit;
use App\Models\Business;
use App\Models\Staff;
use App\Jobs\ComplaintForward;
use App\Mail\StaffOnComplaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complaint = Complaint::where('state',1)->get();
        return ComplaintResource::Collection($complaint);
    }


    public function Complaints()
    {
        $complaint = Complaint::where('state',1)->where('staff_id', Auth::user()->staff_id)->where('status','=','Escalated')->get();
        return ComplaintResource::Collection($complaint);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function BusinessComp($id)
    {
        $complaint = Complaint::where('state',1)->where('business', $id)->latest()->first();
        return new ComplaintResource($complaint);
    }
    public function Thiscomplaint($id)
    {
        $complaint = Complaint::where('state',1)->where('id', $id)->get();
        return ComplaintResource::Collection($complaint);
    }
    public function CountComp($id)
    {
        $arr['totalcomplaints'] = Complaint::where('state',1)->where('business', $id)->count('id');
        $arr['escalated_comp'] =  Complaint::where('state',1)->where('business', $id)->where('status','Escalated')->count('id');
        $arr['resolved_comp'] =  Complaint::where('state',1)->where('business', $id)->where('status','Resolved')->count('id');

        return $arr;
    }

    public function Compcount()
    {
        $arr['complaintcount'] = Complaint::where('state',1)->where('staff_id',Auth::user()->staff_id)->count('id');
        return $arr;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        {
            $request->validate([
                'name' => ['required', 'max:50'],
                'advice' => ['required', 'max:1000'],
                'status' => ['required', 'max:50'],
            ]);
            $complaint = Complaint::create([
                'business' => $request->business,
                'name' => $request->name,
                'detail' => $request->detail??'',
                'advice' => $request->advice,
                'status' => $request->status,
                'staff_id' => $request->staff_id,

            ]);
            if($request->status == 'Escalated' && $request->staff_id){
                $staff = Staff::find($request->staff_id);
                $biz = Business::find($request->business);
                $email = $staff->email;
                $email2 = $biz->email;
                $staffname = $staff->name;
                if($request->detail != ''){
                $msg = "Dear ".$staffname."<br>A complaint <b>".$request->name."</b> with details ".$request->detail." has been submitted to you from <br><b>".$biz->name."</b> of <b>".$biz->priority."</b> priority";
                $msg2 = "Dear ".$biz->name."<br>Your complaint <b>".$request->name."</b> with details ".$request->detail." has been forwarded to <br><b>".$staffname."</b> for follow up";
                }else{
                    $msg = "Dear ".$staffname."<br>A complaint <b>".$request->name."</b> has been submitted to you from <br><b>".$biz->name."</b> of <b>".$biz->priority."</b> priority";
                    $msg2 = "Dear ".$biz->name."<br>Your complaint <b>".$request->name."</b> has been forwarded to <br><b>".$staffname."</b> for follow up";  
                }
                $subject = "Nugsoft Customer complaint";
                ComplaintForward::dispatch(new StaffOnComplaint($email, $msg, $subject), $email);
                ComplaintForward::dispatch(new StaffOnComplaint($email2, $msg2, $subject), $email2);
            }
            $action =  "Added new complaint ".$complaint->name;
            $newstaff = Audit::create([
                'action' => $action,
                'addedby' => Auth::user()->staff_id,
            ]);

            return response([
                'message' => 'Complaint recorded successfully.',
                'status' => 'success',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $upcomplaint = Complaint::find($id)->update([
            'business' => $request->business,
            'branch' => $request->branch,
            'name' => $request->name,
            'detail' => $request->detail,
            'advice' => $request->advice,
            'status' => $request->status,
            'staff_id' => $request->staff_id,

        ]);
        return response([
            'message' => 'Complaint details updated successfully.',
            'status' => 'success',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Complaint::find($id)->update([
            'state' => 0,
        ]);
        return response([
            'message' => 'Complaint deleted successfully.',
            'status' => 'success',
        ], 200);
    }
    public function close($id)
    {
        Complaint::find($id)->update([
            'status' => 'Resolved',
        ]);
        return response([
            'message' => 'Complaint closed successfully.',
            'status' => 'success',
        ], 200);
    }
}
