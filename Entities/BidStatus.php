<?php

namespace Modules\Iad\Entities;


class BidStatus
{
  const PENDING = 0;
  const ACCEPTED = 1;
  const REJECTED = 2;
  const IN_PROGRESS = 3;
  const COMPLETED = 4; 
  const CANCELLED = 5; 
 
  /**
   * @var array
   */
  private $statuses = [];

  public function __construct()
  {
    $this->statuses = [
      self::PENDING => ['title' => trans('iad::bids.status.pending')],
      self::REJECTED => ['title' => trans('iad::bids.status.rejected')],
      self::IN_PROGRESS => ['title' => trans('iad::bids.status.in progress')],
      self::COMPLETED => ['title' => trans('iad::bids.status.completed')],
      self::CANCELLED => ['title' => trans('iad::bids.status.cancelled')]
    ];
  }

  /**
   * Get the available statuses
   * @return array
   */
  public function lists()
  {
    return $this->statuses;
  }

  /**
   * Get the post status
   * @param int $statusId
   * @return string
   */
  public function get($statusId)
  {
    if (isset($this->statuses[$statusId])) {
      return $this->statuses[$statusId];
    }

    return $this->statuses[self::PENDING];
  }

  /**
   * Index Method To API
   */
  public function index()
  {
    //Instance response
    $response = [];
    //AMp status
    foreach ($this->statuses as $key => $status) {
      array_push($response, ['value' => $key, 'label' => $status['title']]);
    }
    //Repsonse
    return collect($response);
  }
  
}
