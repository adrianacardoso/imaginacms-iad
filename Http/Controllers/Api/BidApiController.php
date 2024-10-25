<?php

namespace Modules\Iad\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model
use Modules\Iad\Entities\Bid;
use Modules\Iad\Repositories\BidRepository;

class BidApiController extends BaseCrudController
{
  public $model;
  public $modelRepository;

  public function __construct(Bid $model, BidRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }
}
