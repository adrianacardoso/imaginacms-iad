<?php

namespace Modules\Iad\Entities;

use Modules\Core\Icrud\Entities\CrudModel;

use Modules\Media\Support\Traits\MediaRelation;

class Bid extends CrudModel
{
  
  use MediaRelation;

  protected $table = 'iad__bids';
  public $transformer = 'Modules\Iad\Transformers\BidTransformer';
  public $repository = 'Modules\Iad\Repositories\BidRepository';
  public $requestValidation = [
      'create' => 'Modules\Iad\Http\Requests\CreateBidRequest',
      'update' => 'Modules\Iad\Http\Requests\UpdateBidRequest',
    ];
  //Instance external/internal events to dispatch with extraData
  public $dispatchesEventsWithBindings = [
    //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
    'created' => [],
    'creating' => [],
    'updated' => [],
    'updating' => [],
    'deleting' => [],
    'deleted' => []
  ];
  
  protected $fillable = [
    'ad_id',
    'amount',
    'description',
    'currency',
    'delivery_days',
    'selected',
    'status_id',
    'options'
  ];

  protected $casts = ['options' => 'array'];

  protected $singleFlagName = 'selected';
  protected $singleFlaggableCombination = ['ad_id'];

  /**
   * Relations
   */
  public function ad()
  {
      return $this->belongsTo(Ad::class);
  }

  public function createdByUser()
  {
    $driver = config('asgard.user.config.driver');
    return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User", 'created_by');
  }

  /**
   * Mutators
   */
  public function setOptionsAttribute($value)
  {
    $this->attributes['options'] = json_encode($value);
  }

  /*
  * Accesors
  */
  public function getStatusLabelAttribute()
  {
    return (new BidStatus())->get($this->status_id);
  }

}
