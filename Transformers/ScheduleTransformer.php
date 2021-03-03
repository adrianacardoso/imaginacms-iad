<?php

namespace Modules\Iad\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ScheduleTransformer extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->when($this->id, $this->id),
      'iso' => $this->when($this->iso, $this->iso),
      'name' => $this->when($this->name, $this->name),
      'adId' => $this->when($this->ad_id, $this->ad_id),
      'startTime' => $this->when($this->start_time, $this->start_time),
      'endTime' => $this->when($this->end_time, $this->end_time),
      'options' => $this->when($this->options, $this->options),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];
  }
}
