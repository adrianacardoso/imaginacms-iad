<?php


namespace Modules\Iad\Http\Controllers\Api;

use Illuminate\Http\Request;
use Log;
use Mockery\CountValidator\Exception;
use Modules\Iad\Entities\Ad;
use Modules\Iad\Events\AdIsUpdating;
use Modules\Iad\Http\Requests\CreateUpRequest;
use Modules\Iad\Http\Requests\UpdateUpRequest;
use Modules\Iad\Repositories\UpRepository;
use Modules\Iad\Transformers\UpTransformer;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;
use Modules\Iad\Http\Requests\CreateAdRequest;
use Modules\Iad\Http\Requests\UpdateAdRequest;
use Modules\Iad\Repositories\AdRepository;
use Modules\Iad\Transformers\AdTransformer;
use Route;
use Modules\Iad\Entities\AdStatus;

class UpApiController extends BaseApiController
{
  private $up;
  private $field;

  public function __construct(UpRepository $up, FieldController $field)
  {
    parent::__construct();
    $this->up = $up;
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
      $dataEntity = $this->up->getItemsBy($params);

      //Response
      $response = [
        "data" => UpTransformer::collection($dataEntity)
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
      $dataEntity = $this->up->getItem($criteria, $params);

      //Break if no found item
      if (!$dataEntity) throw new \Exception('Item not found', 404);

      //Response
      $response = ["data" => new UpTransformer($dataEntity)];

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
      
      //Validate Request
      $this->validateRequestApi(new CreateUpRequest((array)$data));

      //Create item
      $up = $this->up->create($data);

      //Response
      $response = ["data" => $up];
      \DB::commit(); //Commit to Data Base
    } catch (\Exception $e) {
      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = [
        "errors" => $e->getMessage(),
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
      $data = $request->input('attributes');
      //Get params
      $params = $this->getParamsRequest($request);
      //Validate Request
      $this->validateRequestApi(new UpdateUpRequest((array)$data));
      

      //Get Parameters from URL.
      $params = $this->getParamsRequest($request);

      //Request to Repository
      $this->up->updateBy($criteria, $data, $params);

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
      $this->up->deleteBy($criteria, $params);

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
