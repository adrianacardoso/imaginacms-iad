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

class UpTransformer extends JsonResource
{
  public function toArray($request)
  {
    $data = [
      'id' => $this->when(isset($this->id), $this->id),
      'title' => $this->when(isset($this->title), $this->title),
      'description' => $this->when(isset($this->description), $this->description),
      'daysLimit' => $this->days_limit,
      'upsDaily' => $this->ups_daily,
      'status' => $this->when(isset($this->status), $this->status),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];
  
    if(is_module_enabled('Icommerce')){
      $productTransformer = 'Modules\\Icommerce\\Transformers\\ProductTransformer';
      $data['productId'] = $this->product ? $this->product->id : '';
      $data['product'] = new $productTransformer($this->whenLoaded('product'));
    }

    // Return data with available translations
    $filter = json_decode($request->filter);
    if (isset($filter->allTranslations) && $filter->allTranslations) {
      // Get langs avaliables
      $languages = \LaravelLocalization::getSupportedLocales();
      foreach ($languages as $lang => $value) {
        $data[$lang]['title'] = $this->hasTranslation($lang) ? $this->translate("$lang")['title'] : '';
        $data[$lang]['description'] = $this->hasTranslation($lang) ? $this->translate("$lang")['description'] : '';
      }
    }

    return $data;
  }
}
