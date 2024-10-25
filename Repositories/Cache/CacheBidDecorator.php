<?php

namespace Modules\Iad\Repositories\Cache;

use Modules\Iad\Repositories\BidRepository;
use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;

class CacheBidDecorator extends BaseCacheCrudDecorator implements BidRepository
{
    public function __construct(BidRepository $bid)
    {
        parent::__construct();
        $this->entityName = 'iad.bids';
        $this->repository = $bid;
    }
}
