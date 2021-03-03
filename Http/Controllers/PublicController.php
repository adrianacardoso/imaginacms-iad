<?php

namespace Modules\Iad\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Route;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;
use Modules\Core\Http\Controllers\BasePublicController;
use Mockery\CountValidator\Exception;

//Entities
use Modules\Iad\Entities\Ad;
use Modules\Iad\Entities\Category;

//Entities
use Modules\Iad\Repositories\AdRepository;

//Transformers
use Modules\Iad\Transformers\AdTransformer;
use Modules\Iad\Transformers\CategoryTransformer;

class PublicController extends BaseApiController
{
  private $ad;
  private $category;

  public function __construct(
    AdRepository $ad,
    Category $category
  )
  {
    parent::__construct();
    $this->category = $category;
    $this->ad = $ad;
  }

  public function createAd()
  {
    //Get categories
    $categories = $this->category->with('translations')->get();

    //Validate view
    $tpl = 'iad::frontend.adForm.create.view';
    $ttpl = 'iad.adForm.create.view';
    if (view()->exists($ttpl)) $tpl = $ttpl;

    //Render
    return view($tpl, ["categories" => $categories->toTree()]);
  }

  // view products by category
  public function index(Request $request)
  {
    $argv = explode("/", $request->path());
    $slug = end($argv);

    $tpl = 'Iad::frontend.index';
    $ttpl = 'Iad.index';

    if (view()->exists($ttpl)) $tpl = $ttpl;

    $category = null;

    $categoryBreadcrumb = [];

    if ($slug && $slug != trans('Iad::routes.ad.index.index')) {

      $category = $this->category->findBySlug($slug);

      if (isset($category->id)) {
        //With nestedset package
        // $categoryBreadcrumb = CategoryTransformer::collection(Category::ancestorsAndSelf($category->id));
        //Without nestedset package
        $categories = [$category];
        $categories = array_merge($categories, $categories->childrens);
        $categoryBreadcrumb = CategoryTransformer::collection($categories);
        //Without nestedset package

        $ptpl = "Iad.category.{$category->parent_id}%.index";
        if ($category->parent_id != 0 && view()->exists($ptpl)) {
          $tpl = $ptpl;
        }

        $ctpl = "Iad.category.{$category->id}.index";
        if (view()->exists($ctpl)) $tpl = $ctpl;

        $ctpl = "Iad.category.{$category->id}%.index";
        if (view()->exists($ctpl)) $tpl = $ctpl;

      } else {
        return response()->view('errors.404', [], 404);
      }

    }

    //$dataRequest = $request->all();

    return view($tpl, compact('category', 'categoryBreadcrumb'));
  }

  /**
   * Show product
   * @param Request $request
   * @return mixed
   */
  public function show(Request $request)
  {
    $argv = explode("/", $request->path());
    $slug = end($argv);

    $tpl = 'Iad::frontend.show';
    $ttpl = 'Iad.show';
    if (view()->exists($ttpl)) $tpl = $ttpl;
    $params = json_decode(json_encode(
      [
        "include" => explode(",", "translations,category,categories"),
        "filter" => [
          "field" => "slug"
        ]
      ]
    ));

    $entity = $this->ad->getItem($slug, $params);

    if ($entity) {
      $category = $entity->category;
      return view($tpl, compact('entity', 'category'));

    } else {
      return response()->view('errors.404', [], 404);
    }

  }

  public function editAd($adId)
  {
    $params = json_decode(json_encode(
      [
        "include" => [],
        "filter" => [
          "field" => "id",
          "user" => \Auth::user()->id
        ]
      ]
    ));
    $ad = $this->ad->getItem($adId, $params);
    if (!$ad)
      abort(404);
    $categories = $this->category->all();
    $services = $this->service->all();
    $tpl = 'Iad::frontend.adForm.edit.view';
    $ttpl = 'Iad.adForm.edit.view';
    if (view()->exists($ttpl)) $tpl = $ttpl;
    return view($tpl, [
      "categories" => $categories,
      "services" => $services,
      "adId" => $adId,
    ]);
  }//createAd

}
