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
use Modules\Iad\Entities\AdStatus;

class AdApiController extends BaseApiController
{
  private $ad;
  private $field;

  public function __construct(AdRepository $ad, FieldController $field)
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
   * GET ITEMS
   *
   * @return mixed
   */
  public function indexStatus(Request $request)
  {
    try {
      //Get Parameters from URL.
      $params = $this->getParamsRequest($request);

      //Request to Repository
      $adStatus = new AdStatus();

      //Response
      $response = ["data" => $adStatus->get()];
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
      $ad = $this->ad->create($data);

      //Response
      $response = ["data" => $ad];
      \DB::commit(); //Commit to Data Base
    } catch (\Exception $e) {
      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = ($status != 403) ? ["errors" => $e->getMessage()] :
        ["messages" => ["type" => 'error', "message" => $e->getMessage()]];
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
