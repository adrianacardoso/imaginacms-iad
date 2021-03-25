<?php

namespace Modules\Iad\View\Components;

use Illuminate\View\Component;

class ListItem extends Component
{

  public $view;
  public $item;
  public $categories;

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
  public function __construct( $item, $mediaImage = "mainimage", $layout = 'iad-list-item-1',
                              $wishlist = true, $city = true, $years = true,
                              $price = true, $pais = true, $likes = true, $numberComments = true)
  {
//    $this->item = $item;
    $this->mediaImage = $mediaImage;
    $this->item = $item;
    $this->view = "iad::frontend.components.list-item.layout.". ( $layout ?? '.iad-list-item-1').".index";
  $this->initCategories();
  }


function getParentAttributes($parentAttributes)
{
  isset($parentAttributes["mediaImage"]) ? $this->mediaImage = $parentAttributes["mediaImage"] : false;

}
  
  /**
   * @return mixed
   */
  public function initCategories()
  {
    $this->categories = $this->categoryRepository()->getItemsBy(json_decode(json_encode([])));
  }
  
  
  /**
   * @return currencyRepository
   */
  private function categoryRepository()
  {
    return app('Modules\Iad\Repositories\CategoryRepository');
  }
/**
 * Get the view / contents that represent the component.
 *
 * @return \Illuminate\Contracts\View\View|string
 */
public function render()
{
  return view($this->view);
}
}
