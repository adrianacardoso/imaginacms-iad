<?php

namespace Modules\Iad\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class UpLog extends Model
{
    use Translatable;

    protected $table = 'iad__up_log';
    public $translatedAttributes = [];
    protected $fillable = [];
}
