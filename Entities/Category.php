<?php

namespace Modules\Iad\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Media\Support\Traits\MediaRelation;

class Category extends Model
{
  use Translatable, NodeTrait, MediaRelation;

  protected $table = 'iad__categories';
  public $translatedAttributes = ['title', 'description', 'slug'];
  protected $fillable = [
    'parent_id',
    'status',
    'options',
    'sort_order'
  ];
  protected $fakeColumns = ['options'];

  protected $casts = [
    'options' => 'array'
  ];

  public function parent()
  {
    return $this->belongsTo('Modules\Iad\Entities\Category', 'parent_id');
  }

  public function children()
  {
    return $this->hasMany('Modules\Iad\Entities\Category', 'parent_id');
  }

  public function ads()
  {
    return $this->belongsToMany(Ad::class, 'iad__ad_category');
  }

  public function getLftName()
  {
    return 'lft';
  }

  public function getRgtName()
  {
    return 'rgt';
  }

  public function getDepthName()
  {
    return 'depth';
  }

  public function getParentIdName()
  {
    return 'parent_id';
  }
}
