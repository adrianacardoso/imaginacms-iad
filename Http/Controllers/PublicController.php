<?php

namespace Modules\Iad\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Modules\Iad\Entities\AdUp;
use Modules\Iad\Repositories\CategoryRepository;
use Modules\Iad\Repositories\UpRepository;
use Modules\Iad\Transformers\AdUpTransformer;
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
  private $up;
  private $category;
  private $categoryRepository;
  
  public function __construct(
    AdRepository $ad,
    UpRepository $up,
    CategoryRepository $categoryRepository,
    Category $category
  )
  {
    parent::__construct();
    $this->category = $category;
    $this->categoryRepository = $categoryRepository;
    $this->ad = $ad;
    $this->up = $up;
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
    
    $tpl = 'iad::frontend.show';
    $ttpl = 'iad.show';
    if (view()->exists($ttpl)) $tpl = $ttpl;
    $params = json_decode(json_encode(
      [
        "include" => explode(",", "city,schedule,fields,categories,translations"),
        "filter" => [
          "field" => "slug"
        ]
      ]
    ));
    
    $item = $this->ad->getItem($slug, $params);
    $categories = $this->categoryRepository->getItemsBy(json_decode(json_encode([])));
    if (isset($item->id)) {
      return view($tpl, compact('item', 'categories'));
      
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
    
    if (!isset($ad->id)) abort(404);
    
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
  
  
  public function buyUp(Request $request, $adSlug)
  {
    
    
    $tpl = 'iad::frontend.buy-up';
    $ttpl = 'iad.buy-up';
    if (view()->exists($ttpl)) $tpl = $ttpl;
    
    $params = json_decode(json_encode(
      [
        "include" => explode(",", "city,schedule,fields,categories,translations"),
        "filter" => [
          "field" => "slug"
        ]
      ]
    ));
    
    $item = $this->ad->getItem($adSlug, $params);
    
    $params = json_decode(json_encode(
      [
        "include" => ["product"],
        "filter" => [
          "status" => 1
        ]
      ]
    ));
    
    $ups = $this->up->getItemsBy($params);
    
    if (isset($item->id)) {
      
      return view($tpl, compact('item', 'ups'));
      
    } else {
      return response()->view('errors.404', [], 404);
    }
    
    
  }
  
  public function buyUpStore(Request $request, $adId)
  {
    $cartService = app("Modules\Icommerce\Services\CartService");
    
    $data = $request->all();

    $params = json_decode(json_encode(
      [
        "include" => ["product"],
        "filter" => [
          "status" => 1
        ]
      ]
    ));
    $up = $this->up->getItem($data["upId"], $params);
  
    $products =   [[
      "id" => $up->product->id,
      "quantity" => 1,
      "options" => array_merge($data,["adId" => $adId])
    ]];
    
    if(isset($data["featured"])){
      array_push($products,[
        "id" => config("asgard.iad.config.featuredProductId"),
        "quantity" => 1,
        "options" => ["adId" => $adId]
      ]);
    }
    $cartService->create([
      "products" => $products
    ]);
    
    $locale = \LaravelLocalization::setLocale() ?: \App::getLocale();
    return redirect()->route($locale . '.icommerce.store.checkout');
  }
  
  public function upPins(Request $request)
  {
    $nowDate = date('Y-m-d');
    $nowHour = date('H:i:s');
  
    
    
    $result = AdUp::select(
        \DB::raw("DATEDIFF(NOW(), from_date) as days_elapsed"),
        \DB::raw("iad__ad_up.*")
      )
      ->where("status", 1)
      ->where("from_date","<=","$nowDate")
      ->where("to_date", ">=", "$nowDate")
      ->where("from_hour", "<=", "$nowHour")
      ->where("to_hour", ">=", "$nowHour")
      ->whereRaw(\DB::raw("ups_counter/ups_daily <= days_limit"))
      ->whereRaw(\DB::raw("DATEDIFF(NOW(), from_date) = TRUNCATE(ups_counter/ups_daily,0)"))
    ->get();
  
    $everyUp = config("asgard.iad.config.everyUp");
    
    foreach ($result as $item){
      $rangeMinutes = (strtotime($item->to_hour) - strtotime($item->from_hour))/60/$everyUp/$item->ups_daily;
  
      $start = strtotime($item->from_hour);
      $end = strtotime(date("H:i:s"));
  
      $nowRange = ($end - $start)/60;
      dd($nowRange,$item->ups_counter/$item->ups_daily,$rangeMinutes);
      return $nowRange >= $item->ups_counter/$item->ups_daily * $rangeMinutes;
    }
    $upsToUpload = $result->filter(function ($item, $key) {
      
      $rangeMinutes = (strtotime($item->to_hour) - strtotime($item->from_hour))/60/$everyUp/$item->ups_daily;
      
      $start = strtotime($item->from_hour);
      $end = strtotime(date("H:i:s"));
      
      $nowRange = ($end - $start)/60;
      return $nowRange >= $item->ups_counter * $rangeMinutes;
    });
    
    dd($upsToUpload, $everyUp);
    //uploading ads
    Ad::whereIn("id",$upsToUpload->pluck("ad_id")->toArray())->update(["uploaded_at" => \DB::raw('NOW()')]);
  
    //updating ups_counter
    AdUp::whereIn("id",$upsToUpload->pluck("id")->toArray())->update(["ups_counter" => \DB::raw('ups_counter+1'),"days_counter" => \DB::raw('TRUNCATE((ups_counter+1)/ups_daily)')]);
 
  
    $upsToDisabled = $result->filter(function ($item, $key) {
      $realDaysElapsed = (int)(($item->ups_counter + 1)/$item->ups_daily);
   
      return $realDaysElapsed != $item->days_elapsed && $realDaysElapsed >= $item->days_limit;
    });
    
    //disabling ad-ups
    AdUp::whereIn("id",$upsToDisabled->pluck("id")->toArray())->update(["status" => 0]);
    
  }
  
}
