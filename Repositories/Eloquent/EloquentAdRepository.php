<?php

namespace Modules\Iad\Repositories\Eloquent;

use Modules\Iad\Repositories\AdRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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

    /*== RELATIONSHIPS ==*/
    if (in_array('*', $params->include)) {//If Request all relationships
      $query->with([]);
    } else {//Especific relationships
      $includeDefault = [];//Default relationships
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

      //Order by
      if (isset($filter->order)) {
        $orderByField = $filter->order->field ?? 'created_at';//Default field
        $orderWay = $filter->order->way ?? 'desc';//Default way
        $query->orderBy($orderByField, $orderWay);//Add order to query
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

      // add filter by nearby
      if (isset($filter->nearby) && $filter->nearby) {
        if (!empty($filter->nearby->lat) && !empty($filter->nearby->lng)) {

          if ($filter->nearby->radio == "all") {

            if (isset($filter->nearby->lat) && isset($filter->nearby->lng) && !empty($filter->nearby->lat) && !empty($filter->nearby->lng)) {
              $query->select("*", \DB::raw("SQRT(
            POW(69.1 * (lat - " . $filter->nearby->lat . "), 2) +
            POW(69.1 * (" . $filter->nearby->lng . " - lng) * COS(lat / 57.3), 2)) AS radio"))
                ->having('radio', '<', 100);
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

      //Filter by status
      if (isset($filter->status) && !empty($filter->status)) {
        $filter->status = Arr::wrap($filter->status);
        $query->whereIn('status', $filter->status);
      }

      //Filter by status
      if (isset($filter->featured) && !empty($filter->featured)) {

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
      $query->with([]);
    } else {//Especific relationships
      $includeDefault = [];//Default relationships
      if (isset($params->include))//merge relations with default relationships
        $includeDefault = array_merge($includeDefault, $params->include);
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

      if(isset($user->id)){
        // if is salesman or salesman manager or salesman sub manager
        $query->where('user_id', $user->id);
  
      }
      
      
    }
  }
}
