<?php

namespace Modules\Iads\View\Components;

use Illuminate\View\Component;

class ListItem extends Component
{

  public $view;
  public $items;

  public $wishlist;

  public $city;
  public $years;

  public $price;
  public $pais;

  public $likes;
  public $numberComments;
  public $videos;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct( $mediaImage = "mainimage", $layout = 'iad-list-item-1',
                              $wishlist = true, $city = true, $years = true,
                              $price = true, $pais = true, $likes = true, $numberComments = true)
  {
//    $this->item = $item;
    $this->mediaImage = $mediaImage;
    $this->view = "iads::frontend.components.list-item.layout.". ( $layout ?? '.iad-list-item-1').".index";

  }


function getParentAttributes($parentAttributes)
{
  isset($parentAttributes["mediaImage"]) ? $this->mediaImage = $parentAttributes["mediaImage"] : false;

}

/**
 * Get the view / contents that represent the component.
 *
 * @return \Illuminate\Contracts\View\View|string
 */
public
function render()
{
  return view($this->view);
}
}
