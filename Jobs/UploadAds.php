<?php

namespace Modules\Iad\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadAds implements ShouldQueue
{
  use InteractsWithQueue, SerializesModels;
  
  
  public function __construct()
  {
    $this->path = $path;
    $this->disk = $disk;
  }
  
  public function handle()
  {
    
    app('log')->info('Uploading Ads');
  
    $nowDate = date('Y-m-d');
    $nowHour = date('H:i:s');
    
    $result = \DB::table("iad__ad_up")
      ->select(
        \DB::raw("DATEDIFF(NOW(), from_date) as days_elapsed"),
        \DB::raw("(ups_counter/ups_daily) as days_elapsed_by_ups"),
        \DB::raw("iad__ad_up.*")
      )
      ->where("status", 1)
      ->where("from_date","<=",$nowDate)
      ->where("to_date", ">=", $nowDate)
      ->where("from_hour", "<=", $nowHour)
      ->where("to_hour", ">=", $nowHour)
      ->whereRaw(\DB::raw("(ups_counter/ups_daily) <= days_limit"))
      ->whereRaw(\DB::raw("DATEDIFF(NOW(), from_date) = TRUNCATE((ups_counter/ups_daily))"));
    
  }
}
