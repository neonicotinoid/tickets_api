<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'id' => $this->resource->id,
            'created_at' => $this->resource->created_at->format('Y-m-d H:m'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:m'),
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'message' => $this->resource->message,
            'status' => $this->resource->status,
            'comment' => $this->resource->comment,
        ];
    }
}
