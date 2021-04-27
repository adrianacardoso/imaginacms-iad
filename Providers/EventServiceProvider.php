<?php

namespace Modules\Iad\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Iad\Events\Handlers\HandleAdStatuses;
use Modules\Iad\Events\Handlers\ProcessOrder;
use Modules\Icommerce\Events\OrderWasProcessed;
use Modules\Iplan\Events\SubscriptionHasFinished;
use Modules\Iplan\Events\SubscriptionHasStarted;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
      OrderWasProcessed::class => [
        ProcessOrder::class
      ],
      SubscriptionHasStarted::class => [
        HandleAdStatuses::class
      ],
      SubscriptionHasFinished::class => [
        HandleAdStatuses::class
      ],
    ];
}
