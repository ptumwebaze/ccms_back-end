<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
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
            'name' => $this->name,
            'product' => $this->product,
            'branchnumber' => $this->branchnumber,
            'branch' => $this->branch?$this->branch:'Main',
            'email' => $this->email,
            'person' => $this->person,
            'contact' => $this->contact,
            'startdate' => $this->startdate,
            'priority' => $this->priority,
            'status' => $this->status,
            // 'complaint' => $this->comp->count('id'),
            'addedon' => $this->created_at->diffForHumans(),
        ];
    }
}
