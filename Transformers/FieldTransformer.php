<?php

namespace Modules\Iad\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Iad\Transformers\AdTransformer;

class FieldTransformer extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->when($this->id, $this->id),
      'name' => $this->when($this->name, $this->name),
      'value' => $this->when(isset($this->value), $this->value),
      'type' => $this->when($this->type, $this->type),
      'adId' => $this->when($this->ad_id, $this->ad_id),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];
  }
}
