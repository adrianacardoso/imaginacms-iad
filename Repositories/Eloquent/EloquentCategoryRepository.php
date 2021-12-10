<?php

namespace Modules\Iad\Repositories\Eloquent;

use Modules\Iad\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Ihelpers\Events\CreateMedia;
use Modules\Ihelpers\Events\DeleteMedia;
use Modules\Ihelpers\Events\UpdateMedia;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{
  public function getItemsBy($params = false)
  {
    /*== initialize query ==*/
    $query = $this->model->query();

    /*== RELATIONSHIPS ==*/
    if (isset($params->include)) {
      if (in_array('*', $params->include)) {//If Request all relationships
        $query->with([]);
      } else {//Especific relationships
        $includeDefault = [];//Default relationships
        if (isset($params->include))//merge relations with default relationships
          $includeDefault = array_merge($includeDefault, $params->include);
        $query->with($includeDefault);//Add Relationships to query
      }
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

      //Filter by parent
      if (isset($filter->parentId)) {
        if (!is_array($filter->parentId)) $filter->parentId = [$filter->parentId];
        $query->whereIn('parent_id', $filter->parentId);
      }

      //Filter search
      if (isset($filter->search)) {
        $query->where(function ($query) use ($filter) {
          $query->whereHas('translations', function ($q) use ($filter) {
            $q->where('title', 'like', "%{$filter->search}%");
          });
        })->orWhere('id', 'like', "%{$filter->search}%");
      }

      //Order by
      if (isset($filter->order)) {
        $orderByField = $filter->order->field ?? 'created_at';//Default field
        $orderWay = $filter->order->way ?? 'desc';//Default way
        $query->orderBy($orderByField, $orderWay);//Add order to query
      }


      //Filter by parent ID
      if (isset($filter->parentId)) {
        if ($filter->parentId == 0) {
          $query->whereNull("parent_id");
        } else {
          $query->where("parent_id", $filter->parentId);
        }
      }

      //Filter by  IDs
      if (isset($filter->ids)) {
        is_array($filter->ids) ? true : $filter->ids = [$filter->ids];
        $query->whereIn('iad__categories.id', $filter->ids);
      }

    }

    /*== FIELDS ==*/
    if (isset($params->fields) && count($params->fields))
      $query->select($params->fields);

    /*== REQUEST ==*/
    if (isset($params->page) && $params->page) {
      return $query->paginate($params->take);
    } else {
      (isset($params->take) && $params->take) ? $query->take($params->take) : false;//Take
      return $query->get();
    }
  }

  public function getItem($criteria, $params = false)
  {
    //Initialize query
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

    /*== FILTER ==*/
    if (isset($params->filter)) {
      $filter = $params->filter;

      if (isset($filter->field))//Filter by specific field
        $field = $filter->field;
    }

    /*== FIELDS ==*/
    if (isset($params->fields) && count($params->fields))
      $query->select($params->fields);

    /*== REQUEST ==*/
    return $query->where($field ?? 'id', $criteria)->first();
  }

  public function create($data)
  {
    $category = $this->model->create($data);

    //Event to ADD media
    event(new CreateMedia($category, $data));

    return $category;
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

    event(new UpdateMedia($model, $data));//Event to Update media
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
    if (isset($model->id)) {
      event(new DeleteMedia($model->id, get_class($model)));//Event to Delete media
      $model ? $model->delete() : false;
    }
  }

  public function findBySlug($slug)
  {
    if (method_exists($this->model, 'translations')) {


      $query = $this->model->whereHas('translations', function (Builder $q) use ($slug) {
        $q->where('slug', $slug);
      })->with('translations', 'parent', 'children');

    } else
      $query = $this->model->where('slug', $slug)->with('translations', 'parent', 'children', 'vehicles');


    if (isset($this->model->tenantWithCentralData) && $this->model->tenantWithCentralData && isset(tenant()->id)) {
      $model = $this->model;
      $query->withoutTenancy();
      $query->where(function ($query) use ($model) {
        $query->where($model->qualifyColumn(BelongsToTenant::$tenantIdColumn), tenant()->getTenantKey())
          ->orWhereNull($model->qualifyColumn(BelongsToTenant::$tenantIdColumn));
      });
    }

    return $query->first();
  }
}
