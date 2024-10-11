<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
          'bank_name' => $this->bank_name,
          'branch_name' => $this->branch_name,
          'city' => $this->city,
          'status' => $this->status,
      ];
  }
}
