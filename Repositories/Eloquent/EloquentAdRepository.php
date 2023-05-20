<?php

namespace Modules\Iad\Repositories\Eloquent;

use Modules\Iad\Entities\Category;
use Modules\Iad\Repositories\AdRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Iad\Entities\Ad;

//Events
use Modules\Ihelpers\Events\CreateMedia;
use Modules\Ihelpers\Events\DeleteMedia;
use Modules\Ihelpers\Events\UpdateMedia;
use Modules\Iad\Events\AdIsCreating;
use Modules\Iad\Events\AdIsDeleting;
use Modules\Iad\Events\AdWasCreated;
use Modules\Iad\Events\AdIsUpdating;
use Modules\Iad\Events\AdWasUpdated;

class EloquentAdRepository extends EloquentBaseRepository implements AdRepository
{
  public function getItemsBy($params = false)
  {
    /*== initialize query ==*/
    $query = $this->model->query();

    //In the autocomplete filter they send the category
    //This relation not exist in AD entity
    $keyCat = array_search("category",$params->include);
    if(!is_null($keyCat)) {
      $params->include[$keyCat] = "categories";
    }

    
    /*== RELATIONSHIPS ==*/
    if (in_array('*', $params->include)) {//If Request all relationships
      $query->with(['files','translations']);
    } else {//Especific relationships
      $includeDefault = ['files','translations'];//Default relationships
      if (isset($params->include))//merge relations with default relationships
        $includeDefault = array_merge($includeDefault, $params->include);
      $query->with($includeDefault);//Add Relationships to query
    }

    /*== FILTERS ==*/
    if (isset($params->filter)) {
      $filter = $params->filter;//Short filter

      //Filter by date
      if (isset($filter->date)) {
        $date = $filter->date;//Short filter date
        $date->field = $date->field ?? 'created_at';
        if (isset($date->from))//From a date
          $query->whereDate($date->field, '>=', $date->from);
        if (isset($date->to))//to a date
          $query->whereDate($date->field, '<=', $date->to);
      }

//      //Order by
//      if (isset($filter->order)) {
//        $orderByField = $filter->order->field ?? 'created_at';//Default field
//        $orderWay = $filter->order->way ?? 'desc';//Default way
//        $query->orderBy($orderByField, $orderWay);//Add order to query
//      }

      //Order by
      if (isset($filter->order) && !empty($filter->order)) {
        $orderByField = $filter->order->field ?? 'created_at';//Default field
        $orderWay = $filter->order->way ?? 'desc';//Default way
        if (in_array($orderByField, ["slug", "name"])) {
          $query->join('iad__ad_translations as translations', 'translations.ad_id', '=', 'iad__ads.id');
          $query->orderBy("translations.{$orderByField}", $orderWay);
        } else
          $query->orderBy($orderByField, $orderWay);//Add order to query
      }

      //Filter by catgeory ID
      if (isset($filter->category) && !empty($filter->category)) {


        $categories = Category::descendantsAndSelf($filter->category);

        if ($categories->isNotEmpty()) {
            $query->where(function ($query) use ($categories) {
              $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('iad__ad_category.category_id', $categories->pluck("id"));
              });
            });
        }
      }

      // add filter by Categories 1 or more than 1, in array/*
      if (isset($filter->categories) && !empty($filter->categories)) {
        is_array($filter->categories) ? true : $filter->categories = [$filter->categories];
        $query->where(function ($query) use ($filter) {
          $query->whereHas('categories', function ($query) use ($filter) {
            $query->whereIn('iad__ad_category.category_id', $filter->categories);
          });
        });

      }

      // add filter by Price Range
      if (isset($filter->priceRange) && !empty($filter->priceRange)) {

        $query->where("min_price", '>=', $filter->priceRange->from);
        $query->where("max_price", '<=', $filter->priceRange->to);

      }

      // add filter by Age Range
      if (isset($filter->ageRange) && !empty($filter->ageRange)) {

        $query->where(function ($query) use ($filter) {
          $query->whereHas('fields', function ($query) use ($filter) {
            $query->where('iad__fields.name', 'age', function ($query) use ($filter) {

              //$query->whereBetween('age',[$filter->ageRange->from,$filter->ageRange->to]);

            })->whereBetween('iad__fields.value', [(int)$filter->ageRange->from, (int)$filter->ageRange->to]);
          });
        });

      }

      // add filter by Antiquity Range
      if (isset($filter->antiquityRange) && !empty($filter->antiquityRange)) {

        $query->where(function ($query) use ($filter) {
          $query->whereHas('fields', function ($query) use ($filter) {
            $query->where('iad__fields.name', 'antiquity', function ($query) use ($filter) {
            })->whereBetween('iad__fields.value', [(int)$filter->antiquityRange->from, (int)$filter->antiquityRange->to]);
          });
        });
      }

      // add filter by nearby
      if (isset($filter->nearby) && $filter->nearby) {

        if(isset($filter->nearby->findByLngLat) && $filter->nearby->findByLngLat==false){

          //dd($filter->nearby);

          $query->whereHas('country', function ($query) use ($filter) {
              $query->where('ilocations__countries.iso_2', $filter->nearby->country);
          });


          //Departments
          if(!is_null($filter->nearby->province)){

            //Cuando se busca "Bogota", google trae dpto Bogota, y esto no existe en el ilocations sino como ciudad
            if($filter->nearby->province!="Bogotá"){
              $query->whereHas('province', function ($query) use ($filter) {
                  $query->leftJoin('ilocations__province_translations as pt', 'pt.province_id', 'ilocations__provinces.id')
                    ->where("pt.name", "like", "%" . $filter->nearby->province . "%");
              });
              \Log::info("Province: ".$filter->nearby->province);
            }else{
              \Log::info("Province: Bogota-Formateo por Ilocations Google");
              //Se agrega ciudad para este caso y no entre en la condicion de neighborhood solo para este caso
              $filter->nearby->city="Bogotá";

            }


          }

          if(isset($filter->nearby->city) && !is_null($filter->nearby->city)){

            $query->whereHas('city', function ($query) use ($filter) {
              $query->leftJoin('ilocations__city_translations as ct', 'ct.city_id', 'ilocations__cities.id')
                ->where("ct.name", "like", "%" . $filter->nearby->city . "%");
            });

            \Log::info("City: ".$filter->nearby->city);

          }

          //Esto es xq google sino se coloca barrio trae la misma localidad para ambas
          if(!isset($filter->nearby->city) || $filter->nearby->neighborhood!=$filter->nearby->city){
            
            
            // Google a veces retorna direcciones como rutas en vez de barrios
            // se formatea para que lo pueda encontrar en el ilocations
            $words = config("asgard.iad.config.location-range.googleWordsMap");
            if(is_null($words))
              $words = array('Av.','Localidad de'); //default - Route Google
            
            $searchResult = trim(str_replace($words,'',$filter->nearby->neighborhood));

            \Log::info("Neighborhood:".$searchResult);

            // Query
            $query->whereHas('neighborhood', function ($query) use ($filter,$searchResult) {
                $query->leftJoin('ilocations__neighborhood_translations as nt', 'nt.neighborhood_id', 'ilocations__neighborhoods.id')
                  ->where("nt.name", "like", "%" . $searchResult . "%");
            });

            //Old
            /*
            $query->whereHas('neighborhood', function ($query) use ($filter) {
                $query->leftJoin('ilocations__neighborhood_translations as nt', 'nt.neighborhood_id', 'ilocations__neighborhoods.id')
                  ->where("nt.name", "like", "%" . $filter->nearby->neighborhood . "%");
            });
            */

          }

        }else{
          

          if (!empty($filter->nearby->lat) && !empty($filter->nearby->lng)) {

            if ($filter->nearby->radio == "all") {

              if (isset($filter->nearby->lat) && isset($filter->nearby->lng) && !empty($filter->nearby->lat) && !empty($filter->nearby->lng)) {
                $query->select("*", \DB::raw("SQRT(
              POW(69.1 * (lat - " . $filter->nearby->lat . "), 2) +
              POW(69.1 * (" . $filter->nearby->lng . " - lng) * COS(lat / 57.3), 2)) AS radio"))
                  ->having('radio', '<', (int)setting('iad::ratioLocationFilter') ?? 20);
              } else {
                if (isset($filter->nearby->country) && !empty($filter->nearby->country)) {
                  $query->whereHas('country', function ($query) use ($filter) {
                    $query->where('ilocations__countries.iso_2', $filter->nearby->country);
                  });
                }
                if (isset($filter->nearby->province) && !empty($filter->nearby->province)) {
                  $query->whereHas('province', function ($query) use ($filter) {
                    $query->leftJoin('ilocations__province_translations as pt', 'pt.province_id', 'ilocations__provinces.id')
                      ->where("pt.name", "like", "%" . $filter->nearby->province . "%");
                  });
                }
                if (isset($filter->nearby->city) && !empty($filter->nearby->city)) {
                  $query->whereHas('city', function ($query) use ($filter) {
                    $query->leftJoin('ilocations__city_translations as ct', 'ct.city_id', 'ilocations__cities.id')
                      ->where("ct.name", "like", "%" . $filter->nearby->city . "%");
                  });
                }
              }
            } else {
              if (!empty($filter->nearby->lat) && !empty($filter->nearby->lng)) {
                $query->select("*", \DB::raw("SQRT(
              POW(69.1 * (lat - " . $filter->nearby->lat . "), 2) +
              POW(69.1 * (" . $filter->nearby->lng . " - lng) * COS(lat / 57.3), 2)) AS radio"))
                  ->having('radio', '<', $filter->nearby->radio);
              }
            }
          }  
        }



       

      }

      //Filter by city id
      // City ID is 0 when name is "ALL / TODOS"
      if (isset($filter->cityId) && $filter->cityId != 0) {
        $query->where("iad__ads.city_id", $filter->cityId);
      }

      //Filter by status
      if (isset($filter->status) && !empty($filter->status)) {
        $filter->status = Arr::wrap($filter->status);
        $query->whereIn('iad__ads.status', $filter->status);
      }

      //Filter by featured
      if (isset($filter->featured) && is_bool($filter->featured)) {
        $query->where('featured', $filter->featured);
      }

      //Filter Search
      if (isset($filter->search) && !empty($filter->search)) {
        $criterion = $filter->search;

        $query->whereHas('translations', function (Builder $q) use ($criterion) {
          $q->where('title', 'like', "%{$criterion}%");
          $q->orWhere('description', 'like', "%{$criterion}%");
        });

      }

      //Filter by userId
      if(isset($filter->userId)){
        $query->where('user_id', $filter->userId);
      }

      if (isset($filter->id)) {
        !is_array($filter->id) ? $filter->id = [$filter->id] : false;
        $query->where('id', $filter->id);
      }
    }

    //Order by "Sort order"
    if (!isset($params->filter->noSortOrder) || !$params->filter->noSortOrder) {
      $query->orderBy('sort_order', 'desc');//Add order to query
    }

  
    $this->validateIndexAllPermission($query, $params);

    /*== FIELDS ==*/
    if (isset($params->fields) && count($params->fields))
      $query->select($params->fields);
    //dd($query->toSql(),$query->getBindings());
    /*== REQUEST ==*/

    if (isset($params->page) && $params->page) {
      //return $query->paginate($params->take);
      return $query->paginate($params->take, ['*'], null, $params->page);
    } else {
      $params->take ? $query->take($params->take) : false;//Take
      return $query->get();
    }
  }

  public function getItem($criteria, $params = false)
  {
    //Initialize query
    $query = $this->model->query();

    /*== RELATIONSHIPS ==*/
    if (in_array('*', $params->include ?? [])) {//If Request all relationships
      $query->with(['files','translations']);
    } else {//Especific relationships
      $includeDefault = ['files','translations'];//Default relationships
      if (isset($params->include))//merge relations with default relationships
        $includeDefault = array_merge($includeDefault, $params->include ?? []);
      $query->with($includeDefault);//Add Relationships to query
    }

    /*== FILTER ==*/
    if (isset($params->filter)) {
      $filter = $params->filter;

      // find translatable attributes
      $translatedAttributes = $this->model->translatedAttributes;

      if (isset($filter->field))
        $field = $filter->field;

      if (isset($field) && in_array($field, $translatedAttributes))//Filter by slug
        $query->whereHas('translations', function ($query) use ($criteria, $filter, $field) {
          $query->where('locale', $filter->locale ?? \App::getLocale())
            ->where($field, $criteria);
        });
      else
        // find by specific attribute or by id
        $query->where($field ?? 'id', $criteria);
    }

    /*== FIELDS ==*/
    if (isset($params->fields) && count($params->fields))
      $query->select($params->fields);

    $this->validateIndexAllPermission($query, $params);

    if (!isset($params->filter->field)) {
      $query->where('id', $criteria);
    }

    //dd($query->toSql(),$query->getBindings());
    /*== REQUEST ==*/
    return $query->first();
  }

  public function create($data)
  {
    //Dispatch event "isCreating"
    event(new AdIsCreating($data));

    //Create model
    $ad = $this->model->create($data);

    //Sync Categories
    $ad->categories()->sync($data['categories']);

    //Sync Fields
    $ad->fields()->createMany($data['fields']);

    //Sync Schedule
    $ad->schedule()->createMany($data['schedule']);

    //Event to save media
    event(new CreateMedia($ad, $data));

    event(new AdWasCreated($ad));

    return $ad;
  }

  public function updateBy($criteria, $data, $params = false)
  {
    /*== initialize query ==*/
    $query = $this->model->query();

    /*== FILTER ==*/
    if (isset($params->filter)) {
      $filter = $params->filter;

      //Update by field
      if (isset($filter->field))
        $field = $filter->field;
    }

    /*== REQUEST ==*/
    $model = $query->where($field ?? 'id', $criteria)->first();

    //Sync Categories
    $model->categories()->sync($data['categories']);

    //Sync Fields (Has Many)
    $model->fields()->delete();
    $model->fields()->createMany($data['fields']);

    //Sync Schedule
    $model->schedule()->delete();
    $model->schedule()->createMany($data['schedule']);

    //Event to save media
    event(new UpdateMedia($model, $data));

    if (isset($data["uploaded_at"]))
      unset($data["uploaded_at"]);

    return $model ? $model->update((array)$data) : false;
  }

  public function deleteBy($criteria, $params = false)
  {


    /*== initialize query ==*/
    $query = $this->model->query();

    /*== FILTER ==*/
    if (isset($params->filter)) {
      $filter = $params->filter;

      if (isset($filter->field))//Where field
        $field = $filter->field;
    }

    /*== REQUEST ==*/
    $model = $query->where($field ?? 'id', $criteria)->first();
    //Dispatch event "isCreating"
    event(new AdIsDeleting($model));
    event(new DeleteMedia($model->id, get_class($model)));//Event to Delete media
    $model ? $model->delete() : false;
  }

  public function getPriceRange($params = false)
  {
    isset($params->take) ? $params->take = false : false;
    isset($params->page) ? $params->page = null : false;
    isset($params->include) ? $params->include = [] : false;

    isset($params->filter->priceRange) ? $params->filter->priceRange = null : false;

    if (isset($params->filter->order)) $params->filter->order = false;
    isset($params->filter) ? empty($params->filter) ? $params->filter = (object)["noSortOrder" => true] : $params->filter->noSortOrder = true : false;
    $params->onlyQuery = true;
    $params->order = false;

    $query = $this->getItemsBy($params);

    $query->select(
      \DB::raw("MIN(iad__ads.min_price) AS minPrice"),
      \DB::raw("MAX(iad__ads.max_price) AS maxPrice")
    );

    return $query->first();
  }


  function validateIndexAllPermission(&$query, $params)
  {
    // filter by permission: index all leads

    if (!isset($params->permissions['iad.ads.index-all']) ||
      (isset($params->permissions['iad.ads.index-all']) &&
        !$params->permissions['iad.ads.index-all'])) {
      $user = $params->user ?? null;

      if (isset($user->id)) {
        // if is salesman or salesman manager or salesman sub manager
        $query->where('user_id', $user->id);

      }


    }
  }
}
