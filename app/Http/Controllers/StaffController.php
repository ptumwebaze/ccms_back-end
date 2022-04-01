<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivationCode;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Audit;
use App\Models\Staff;
use Illuminate\Support\Facades\Redirect;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = Staff::where('status',1)->get();
        return StaffResource::Collection($staff);
    }

    public function userstaff()
    {
        $user = User::all();
        $staffuser = Staff::where('status',1)->doesntHave('user')->get();

        return StaffResource::Collection($staffuser);
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
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email'],
            'contact' => ['required', 'max:50'],
            'position' => ['required', 'max:50'],
        ]);
        if ($request->email) {
            $email = Staff::where('email', $request->email)->first();
            if ($email) {
                return response([
                    'message' => 'Staff member with this email exists.',
                ], 409);
            }
        }
        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'position' => $request->position,
            'addedby' => Auth::user()->id,

        ]);
        return response([
            'message' => 'Staff registered successfully.',
            'status' => 'success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $staff)
    {
        $upstaff = Staff::find($staff)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'position' => $request->position,
        ]);
        return response([
            'message' => 'Staff details updated successfully.',
            'status' => 'success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Staff::find($id)->update([
            'status' => 0,
        ]);
        return response([
            'message' => 'Staff deleted successfully.',
            'status' => 'success',
        ], 200);
    }
}
