<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Complaint;
use App\Http\Resources\BusinessResource;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business = Business::where('status',1)->get();
        return BusinessResource::Collection($business);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


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
            'product' => ['required', 'max:50'],
            'branchnumber' => ['required', 'max:50'],
            'branch' => ['required', 'max:50'],
            'email' => ['required', 'max:50'],
            'person' => ['required', 'max:50'],
            'contact' => ['required', 'max:50'],
            'startdate' => ['required', 'max:50'],
            'priority' => ['required', 'max:50'],
        ]);
        if ($request->email) {
            $email = Business::where('email', $request->email)->first();
            if ($email) {
                return response([
                    'message' => 'Business with this email exists.',
                ], 409);
            }
        }
        $business = Business::create([
            'name' => $request->name,
            'product' => $request->product,
            'branchnumber' => $request->branchnumber,
            'branch' => $request->branch,
            'email' => $request->email,
            'person' => $request->person,
            'contact' => $request->contact,
            'startdate' => $request->startdate,
            'priority' => $request->priority,

        ]);
        return response([
            'message' => 'Business registered successfully.',
            'status' => 'success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $business)
    {
        $upbusiness = Business::find($business)->update([
            'name' => $request->name,
            'product' => $request->product,
            'branchnumber' => $request->branchnumber,
            'branch' => $request->branch,
            'email' => $request->email,
            'person' => $request->person,
            'contact' => $request->contact,
            'startdate' => $request->startdate,
            'priority' => $request->priority,
        ]);
        return response([
            'message' => 'Business details updated successfully.',
            'status' => 'success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy($business)
    {
        Staff::find($business)->update([
            'status' => 0,
        ]);
        return response([
            'message' => 'Business deleted successfully.',
            'status' => 'success',
        ], 200);
    }
}
