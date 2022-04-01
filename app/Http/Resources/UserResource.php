<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->userstaff->name,
            'email' => $this->email,
            'createdon' => $this->created_at->diffForHumans(),


        ];
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activateacc(Request $request)
    {
        $request->validate([
            'code' => ['required', 'max:6'],
            'user_id' => ['required'],
        ]);
        $ActivationCode = ActivationCode::where('code',$request->code)->where('user_id', $request->user_id)->first();
        if($ActivationCode){
            $user = User::find($request->user_id)->update([
                'verified' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);

            $user = User::find($request->user_id);

            $token = $user->createToken($request->device_name ?? 'chat-token')->plainTextToken;
            ActivationCode::destroy($ActivationCode->id);
            return response([
                'user' => new UserResource($user),

                'status' => 'success',
                'token' => $token,
            ], 200);
        }
        return response([
            'status' => 'failed',
            'msg' => 'Wrong activation code',
        ], 404);

    }
}
