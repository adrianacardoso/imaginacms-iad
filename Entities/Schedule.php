<?php

namespace Modules\Iad\Entities;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
  protected $table = 'iad__schedules';
  public $translatedAttributes = [];
  protected $fillable = [
    'iso',
    'name',
    'ad_id',
    'start_time',
    'end_time',
    'options'
  ];
  protected $fakeColumns = ['options'];
  protected $casts = [
    'options' => 'array'
  ];
}
