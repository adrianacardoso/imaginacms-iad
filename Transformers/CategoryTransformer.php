<?php

namespace Modules\Iad\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryTransformer extends JsonResource
{
  public function toArray($request)
  {
    $data = [
      'id' => $this->when($this->id, $this->id),
      'title' => $this->when($this->title, $this->title),
      'description' => $this->when($this->description, $this->description),
      'slug' => $this->when($this->slug, $this->slug),
      'status' => $this->when($this->status, $this->status),
      'parentId' => $this->parent_id ?? 0,
      'parent' => new CategoryTransformer($this->whenLoaded('parent')),
      'mediaFiles' => $this->mediaFiles(),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];

    // Return data with available translations
    $filter = json_decode($request->filter);
    if (isset($filter->allTranslations) && $filter->allTranslations) {
      // Get langs avaliables
      $languages = \LaravelLocalization::getSupportedLocales();
      foreach ($languages as $lang => $value) {
        $data[$lang]['title'] = $this->hasTranslation($lang) ? $this->translate("$lang")['title'] : '';
        $data[$lang]['slug'] = $this->hasTranslation($lang) ? $this->translate("$lang")['slug'] : '';
        $data[$lang]['description'] = $this->hasTranslation($lang) ? $this->translate("$lang")['description'] : '';
      }
    }

    return $data;
  }
}
