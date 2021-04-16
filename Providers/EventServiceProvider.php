<?php

namespace Modules\Iad\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Iad\Events\Handlers\ProcessOrder;
use Modules\Icommerce\Events\OrderWasProcessed;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
      OrderWasProcessed::class => [
      ProcessOrder::class
      ]
    ];
}
