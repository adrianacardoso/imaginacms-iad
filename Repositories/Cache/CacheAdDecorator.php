<?php

namespace Modules\Iad\Repositories\Cache;

use Modules\Iad\Repositories\AdRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheAdDecorator extends BaseCacheDecorator implements AdRepository
{
    public function __construct(AdRepository $ad)
    {
        parent::__construct();
        $this->entityName = 'iad.ads';
        $this->repository = $ad;
    }
}
