<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivationCode;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Audit;
use App\Models\Staff;
use App\Jobs\PasswordResetJob;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('status',1)->get();
        return UserResource::Collection($user);
    }


    public function Login(Request $request)
    {
      $fields =  $request->validate([
            'email' => ['required', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'These credentials do not match any users account.',
                'status' => 'failed',
            ], 404);
        }
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $state = User::where('email','=',$fields['email'])->where('status',0)->first();

        if($state){
            return response([
                'message' => 'This account is suspended, contact the system administrator.',
                'status' => 'failed',
            ], 404);
        }
        if($user->verified == '0'){
            return response([
                'message' => 'Account is not yet verified.',
                'status' => 'verification',
            ], 404);
        }
        $token = $user->createToken($request->device_name ?? 'chat-token')->plainTextToken;
        return response([
            'user' => $user,
            'status' => 'success',
            'token' => $token,
        ], 200);
    }

    public function authenticated(){
        $authuser = Auth::user();
        return new UserResource($authuser);
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
    public function store(Request $request) {
        $user = Staff::where('id','=',$request->staff_id)->first();
        if ($user->email) {
            $email = User::where('email', $user->email)->first();
            if ($email) {
                return response([
                    'message' => 'User with this email exists.',
                ], 404);
            }
        }

        // CREATE USER
                $user = User::create([
                    'email' => $user->email,
                    'staff_id' => $request->staff_id,
                    'password' => Hash::make($request->password),
                ]);
                $que = User::where('email','=',$user->email)->first();
                $action =  "New user ". $request->name." successfully registered";
                $pay = Audit::create([
                    'action' => $action,
                    'addedby' => $que->id,
                ]);

                return response([
                    'message' => 'User created successfully',
                ], 200);

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
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'msg'=>"user loggedout successfully",
        ];
    }

    public function reset(Request $request)
    {

        $date = date('d-m-Y H:i');
        $oldcode = PasswordReset::where('email',$request->email)->where('expiry','>',$date)->first();
        $userdetails = User::where('email',$request->email)->where('status',1)->first();
        if($oldcode){
        $code = $oldcode->code;
        }
        else{
        $code = date('sih');
        }
        if($userdetails){
            $date = date('d-m-Y H:i', strtotime("+5 min"));
            $resetdetails = PasswordReset::create([
                'email' => $request->email,
                'user_id' => $userdetails->id,
                'code' => $code,
                'expiry'=>$date
            ]);
            $user = Staff::find( $userdetails->staff_id);
            $email = $user->email;
            $username = $user->name;
            $msg = "Dear ".$username." <br> Use the code <b>".$resetdetails->code."</b> to reset your password for Customer Complaints Management System. <b>It expires in 5 minutes time</b>";
            $subject = "Password Reset";
            PasswordResetJob::dispatch(new PasswordResetMail($email, $msg, $subject), $email);
            return response([
                'message' => 'Code has been sent to your email.',
                'status' => 'success',
            ], 200);
            $request->status = true;
        }
        return response([
            'message' => 'Error in execution, check your email and try again.',
            'status' => 'error',
        ], 404);

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
        $user = User::find($id)->update([
            'email' => $request->email,

        ]);
        return response([
            'message' => 'User updated successfully.',
            'status' => 'success',
        ], 200);
    }

    public function codesub(Request $request)
    {
        $date = date('d-m-Y H:i');
        $Newcode = PasswordReset::where('code','=',$request->code)->where('email','=',$request->email)->where('expiry','<', $date)->first();
        if($Newcode){
            return response([
                'message' => 'Code confirmed, please set a new password.',
                'status' => 'success',
            ], 200);
        }
        else{
            return response([
                'message' => 'This code doesnot exist or has expired.',
                'status' => 'error',
            ], 404);
        }
    }

    public function updatepass(Request $request)
    {

        $Newcode = PasswordReset::where('code', $request->code)->where('email', $request->email)->first();
        if($Newcode){
            $user = User::where('email', $request->email)->update([
                'password' => Hash::make($request->password),

            ]);
            return response([
                'message' => 'Password updated successfully.',
                'status' => 'success',
            ], 200);
        }
        // return response([
        //     'message' => 'Password update unsuccessful',
        //     'status' => 'error',
        // ], 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->update([
            'status' => 0,
        ]);
        return response([
            'message' => 'User deleted successfully.',
            'status' => 'success',
        ], 200);
    }
}
