<?php

namespace Modules\Iad\Repositories\Cache;

use Modules\Iad\Repositories\ScheduleRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheScheduleDecorator extends BaseCacheDecorator implements ScheduleRepository
{
    public function __construct(ScheduleRepository $schedule)
    {
        parent::__construct();
        $this->entityName = 'iad.schedules';
        $this->repository = $schedule;
    }
}
