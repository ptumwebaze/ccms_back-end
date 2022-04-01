<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  Carbon\Carbon;

class ComplaintResource extends JsonResource
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
            'id' => $this->id?$this->id:'0',
            'business' => $this->buz->name,
            'business_id' => $this->business,
            'branch' => $this->buz->branch,
            'name' => $this->name?$this->name:'No complaints',
            'detail' => $this->detail,
            'advice' => $this->advice,
            'status' => $this->status,
            'staff' => $this->staff_id?$this->forward->name:'--',
            'addedon' => $this->created_at->diffForHumans(),
            'registeredon' =>  Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
