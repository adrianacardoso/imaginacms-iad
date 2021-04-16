<?php

namespace Modules\Iad\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Up extends Model
{
  use Translatable;
  
  protected $table = 'iad__ups';
  
  public $translatedAttributes = [
    'title',
    'description',
  ];
  protected $fillable = [
    'days_limit',
    'ups_daily',
    'status',
  ];
  
  public function product()
  {
    return $this->belongsTo(\Modules\Icommerce\Entities\Product::class,"id","entity_id");
  }
}
