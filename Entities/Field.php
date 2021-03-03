<?php

namespace Modules\Iad\Entities;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
  protected $table = 'iad__fields';
  public $translatedAttributes = [];
  protected $fillable = [
    'name',
    'ad_id',
    'value',
    'type'
  ];
  protected $fakeColumns = ['value'];
}
