<?php

namespace Modules\Iad\Entities;

/**
 * Class Status
 * @package Modules\Iad\Entities
 */
class AdUpStatus
{
  const PENDING = 0;
  const ENABLED = 1;
  const DISABLED = 2;
  
  /**
   * @var array
   */
  private $statuses = [];

  public function __construct()
  {
    $this->statuses = [
      self::PENDING => trans('iad::adups.status.pending'),
      self::DISABLED => trans('iad::adups.status.disabled'),
      self::ENABLED => trans('iad::adups.status.enabled'),
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

    return $this->statuses[self::DISABLED];
  }
}
