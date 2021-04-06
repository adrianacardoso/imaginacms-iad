<?php

namespace Modules\Iad\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class AdUp extends Model
{

    protected $table = 'iad__ad_up';
    protected $fillable = [
      'ad_id',
      'up_id',
      'days_limit',
      'ups_daily',
      'status',
      'order_id',
      'days_counter',
      'ups_counter',
      'from_date',
      'to_date',
      'from_hour',
      'to_hour',
    ];
  
  public function up()
  {
    
    return $this->belongsTo(Up::class);
  }
}
