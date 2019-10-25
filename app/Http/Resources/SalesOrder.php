<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Store;
use App\Status;

class SalesOrder extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'so_no' => str_pad($this->so_no, 8, "0", STR_PAD_LEFT),
            'description' => $this->description,
            'created_at' => $this->created_at->format('m/d/Y H:i:s A'),
            'store_id'=> $this->getStore($this->store_id),
            'delivery_date' => $this->delivery_date,
            'status_id' => $this->getStatus($this->status_id),
        ];
        // return parent::toArray($request);
    }

    protected function getStore($id) {
      $store = Store::findOrFail($id);

      return $store->name;
    }

    protected function getStatus($id) {
      $status = Status::findOrFail($id);

      return $status->name;
    }
}
