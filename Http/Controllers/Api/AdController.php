<?php


namespace Modules\Iad\Http\Controllers\Api;

use Illuminate\Http\Request;
use Log;
use Mockery\CountValidator\Exception;
use Modules\Iad\Entities\Ad;
use Modules\Iad\Events\AdIsUpdating;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;
use Modules\Iad\Http\Requests\CreateAdRequest;
use Modules\Iad\Http\Requests\UpdateAdRequest;
use Modules\Iad\Repositories\AdRepository;
use Modules\Iad\Transformers\AdTransformer;
use Route;

class AdController extends BaseApiController
{
  private $ad;
  private $field;

  public function __construct(
    AdRepository $ad,
    FieldController $field
  )
  {
    parent::__construct();
    $this->ad = $ad;
    $this->field = $field;

  }


  /**
   * GET ITEMS
   *
   * @return mixed
   */
  public function index(Request $request)
  {
    try {
      //Get Parameters from URL.
      $params = $this->getParamsRequest($request);

      //Request to Repository
      $dataEntity = $this->ad->getItemsBy($params);

      //Response
      $response = [
        "data" => AdTransformer::collection($dataEntity)
      ];

      //If request pagination add meta-page
      $params->page ? $response["meta"] = ["page" => $this->pageTransformer($dataEntity)] : false;
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }

    //Return response
    return response()->json($response, $status ?? 200);
  }

  /**
   * GET A ITEM
   *
   * @param $criteria
   * @return mixed
   */
  public function show($criteria, Request $request)
  {
    try {
      //Get Parameters from URL.
      $params = $this->getParamsRequest($request);

      //Request to Repository
      $dataEntity = $this->ad->getItem($criteria, $params);

      //Break if no found item
      if (!$dataEntity) throw new \Exception('Item not found', 404);

      //Response
      $response = ["data" => new AdTransformer($dataEntity)];

    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }

    //Return response
    return response()->json($response, $status ?? 200);
  }

  /**
   * CREATE A ITEM
   *
   * @param Request $request
   * @return mixed
   */
  public function create(Request $request)
  {
    \DB::beginTransaction();
    try {
      //Get data
      $data = $request->input('attributes');
      //dd('allowed limits', allowedLimits(new Ad($data)));
      //Validate Request
      $this->validateRequestApi(new CreateAdRequest((array)$data));

      //Limit plan status
      // if(!iplans_allowLimitPlan(Ad::class,"status",0)){
      //   throw new \Exception('Has alcanzado el límite de creación de anuncios.', 422);
      // }//iplans_allowLimitPlan()

      //Create item
      $entity = $this->ad->create($data);

      //Create fields
      if (isset($data["fields"])) {
        if (count($data['fields']) > 0 && !isset($data['fields'][0]['name'])) {
          //Get by quasar crud
          foreach ($data["fields"] as $key => $value) {
            $field['name'] = $key;
            $field['value'] = $value;
            $field['ad_id'] = $entity->id;// Add ad Id
            $field['user_id'] = $data['user_id'];// Add user Id
            $this->validateResponseApi(
              $this->field->create(new Request(['attributes' => (array)$field]))
            );
          }
        } else {
          //Get by blade front
          foreach ($data["fields"] as $field) {
            $field['ad_id'] = $entity->id;// Add ad Id
            $field['user_id'] = $data['user_id'];// Add user Id
            $this->validateResponseApi(
              $this->field->create(new Request(['attributes' => (array)$field]))
            );
          }
        }//else
      }

      //Response
      $response = ["data" => $entity];
      \DB::commit(); //Commit to Data Base
    } catch (\Exception $e) {
      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = [
        "errors" => $e->getMessage(),
        // "line" => $e->getLine(),
        // "file" => $e->getFile(),
        // "trace" => $e->getTrace(),
      ];
    }
    //Return response
    return response()->json($response, $status ?? 200);
  }

  /**
   * UPDATE ITEM
   *
   * @param $criteria
   * @param Request $request
   * @return mixed
   */
  public function update($criteria, Request $request)
  {
    \DB::beginTransaction(); //DB Transaction
    try {
      //Get data
      $params = $this->getParamsRequest($request);
      $data = $request->input('attributes');
      $model = $this->ad->getItem($criteria, $params);
      event(new AdIsUpdating($data, $model));
      //Validate Request
      $this->validateRequestApi(new UpdateAdRequest((array)$data));

      // //Limit plan status
      // if(isset($data['status']) && !iplans_allowLimitPlan(Ad::class,"status",$data['status'],$criteria)){
      //   throw new \Exception('Has alcanzado el límite de creación de anuncios con ese estado.', 422);
      // }//iplans_allowLimitPlan()
      //
      // //Limit plan featured
      // if(isset($data['featured']) && !iplans_allowLimitPlan(Ad::class,"featured",$data['featured'],$criteria)){
      //   throw new \Exception('Has alcanzado el límite de creación destacados.', 422);
      // }//iplans_allowLimitPlan()

      //Get Parameters from URL.
      $params = $this->getParamsRequest($request);

      //Request to Repository
      $this->ad->updateBy($criteria, $data, $params);
      //Create or Update fields
      if (isset($data["fields"])) {
        foreach ($data["fields"] as &$field) {
          $field = (array)$field;
          if (is_bool($field["value"]) || (isset($field["value"]) && !empty($field["value"]))) {
            $field['ad_id'] = $criteria;// Add ad Id
            if (!isset($field["id"])) {
              $this->validateResponseApi(
                $this->field->create(new Request(['attributes' => $field]))
              );
            } else {
              $this->validateResponseApi(
                $this->field->update($field["id"], new Request(['attributes' => $field]))
              );
            }

          } else {
            // if (isset($field['id'])) {
            //   $this->validateResponseApi(
            //     $this->field->delete($field['id'], new Request(['attributes' => $field]))
            //   );
            // }
          }
        }
      }

      //Response
      $response = ["data" => 'Item Updated'];
      \DB::commit();//Commit to DataBase
    } catch (\Exception $e) {
      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = [
        "errors" => $e->getMessage(),
        // "trace" => $e->getTrace(),
      ];
      \Log::error($response);
    }

    //Return response
    return response()->json($response, $status ?? 200);
  }


  /**
   * DELETE A ITEM
   *
   * @param $criteria
   * @return mixed
   */
  public function delete($criteria, Request $request)
  {
    \DB::beginTransaction();
    try {
      //Get params
      $params = $this->getParamsRequest($request);

      //call Method delete
      $this->ad->deleteBy($criteria, $params);

      //Response
      $response = ["data" => ""];
      \DB::commit();//Commit to Data Base
    } catch (\Exception $e) {
      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }

    //Return response
    return response()->json($response, $status ?? 200);
  }


}
