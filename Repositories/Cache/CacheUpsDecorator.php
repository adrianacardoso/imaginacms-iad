<?php

namespace Modules\Iad\Repositories\Cache;

use Modules\Iad\Repositories\UpsRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheUpsDecorator extends BaseCacheDecorator implements UpsRepository
{
    public function __construct(UpsRepository $ups)
    {
        parent::__construct();
        $this->entityName = 'iad.ups';
        $this->repository = $ups;
    }
}
