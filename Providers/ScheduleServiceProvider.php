<?php

namespace Modules\Iad\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Iad\Jobs\UploadAds;

class ScheduleServiceProvider extends ServiceProvider
{
  public function boot()
  {
  
    if(setting("iad::activateUploadsJob", null, false)){
      $this->app->booted(function () {
        $schedule = $this->app->make(Schedule::class);
        $schedule->call(function () {
          \Modules\Iad\Jobs\UploadAds::dispatch();
        })->everyMinute();
      });
    }
    
    

  }
  
}