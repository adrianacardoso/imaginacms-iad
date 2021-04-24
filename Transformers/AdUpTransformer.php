<?php

namespace Modules\Iad\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use Modules\Iprofile\Transformers\UserTransformer;
use Modules\Ilocations\Transformers\CountryTransformer;
use Modules\Ilocations\Transformers\ProvinceTransformer;
use Modules\Ilocations\Transformers\CityTransformer;
use Modules\Iad\Transformers\CategoryTransformer;
use Modules\Iad\Transformers\FieldTransformer;
use Modules\Iad\Transformers\ScheduleTransformer;

class AdUpTransformer extends JsonResource
{
  public function toArray($request)
  {
    $data = [
      'id' => $this->when(isset($this->id), $this->id),
      'adId' => $this->when(isset($this->ad_id), $this->ad_id),
      'upId' => $this->when(isset($this->up_id), $this->up_id),
      'orderId' => $this->when(isset($this->order_id), $this->order_id),
      'status' => $this->when(isset($this->status), $this->status),
      'rangeMinutes' => $this->range_minutes,
      'nextUpload' => $this->next_upload,
      'daysLimit' => $this->days_limit,
      'upsDaily' => $this->ups_daily,
      'daysCounter' => $this->days_counter,
      'upsCounter' => $this->ups_counter,
      'fromDate' => $this->from_date,
      'toDate' => $this->to_date,
      'fromHour' => $this->from_hour,
      'toHour' => $this->to_hour,
      'up' =>  new UpTransformer($this->whenLoaded('up')),
      'ad' =>  new AdTransformer($this->whenLoaded('ad')),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];
    

    return $data;
  }
}
