<?php

namespace Modules\Iad\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Iad\Repositories\AdRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheAdDecorator extends BaseCacheCrudDecorator implements AdRepository
{
  public function __construct(AdRepository $ad)
  {
    parent::__construct();
    $this->entityName = 'iad.ads';
    $this->repository = $ad;
  }

  /**
   * Min and Max Price
   *
   * @return collection
   */
  public function getPriceRange($params)
  {
    return $this->remember(function () use ($params) {
      return $this->repository->getPriceRange($params);
    });
  }

}
