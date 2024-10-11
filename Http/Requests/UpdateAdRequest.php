<?php

namespace Modules\Iad\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;
use Modules\Ihelpers\Rules\UniqueSlugRule;

class UpdateAdRequest extends BaseFormRequest
{
  public function rules()
  {
    return [
      'title' => 'min:2',
      'slug' => [new UniqueSlugRule("iad__ad_translations", $this->id, "ad_id"), "min:2", "alpha_dash:ascii"],
      'description' => 'min:2',
    ];
  }

  public function translationRules()
  {
    return [];
  }

  public function authorize()
  {
    return true;
  }

  public function messages()
  {
    return [];
  }

  public function translationMessages()
  {
    return [
      // title
      'title.required' => trans('iad::common.messages.field required'),
      'title.min:2' => trans('iad::common.messages.min 2 characters'),

      // slug
      'slug.required' => trans('iad::common.messages.field required'),
      'slug.min:2' => trans('iad::common.messages.min 2 characters'),

      // description
      'description.required' => trans('iad::common.messages.field required'),
      'description.min:2' => trans('iad::common.messages.min 2 characters'),
    ];
  }
}
