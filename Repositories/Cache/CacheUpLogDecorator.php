<?php

namespace Modules\Iad\Repositories\Cache;

use Modules\Iad\Repositories\UpLogRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheUpLogDecorator extends BaseCacheDecorator implements UpLogRepository
{
    public function __construct(UpLogRepository $uplog)
    {
        parent::__construct();
        $this->entityName = 'iad.uplogs';
        $this->repository = $uplog;
    }
}
