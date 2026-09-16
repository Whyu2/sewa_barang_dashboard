<?php
namespace App\Http\Controllers\Api;
use App\Services\TransactionLogService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;
class TransactionLogController extends BaseController
{
    use ApiResponse;
    public function __construct(protected TransactionLogService $service){}
    #[OA\Get(path:"/transaction-logs", operationId:"getTransactionLogs", tags:["Logs"], summary:"All transaction logs", security:[["bearerAuth"=>[]]], responses:[new OA\Response(response:200, description:"Berhasil")])]
    public function logs(){ return $this->success($this->service->all()); }
    #[OA\Get(path:"/transaction-logs-paginated", operationId:"getTransactionLogsPaginated", tags:["Logs"], summary:"Paginated logs", security:[["bearerAuth"=>[]]], parameters:[new OA\Parameter(name:"limit", in:"query", schema:new OA\Schema(type:"integer")), new OA\Parameter(name:"search", in:"query", schema:new OA\Schema(type:"string")), new OA\Parameter(name:"action", in:"query", schema:new OA\Schema(type:"string")), new OA\Parameter(name:"from", in:"query", schema:new OA\Schema(type:"string")), new OA\Parameter(name:"to", in:"query", schema:new OA\Schema(type:"string"))], responses:[new OA\Response(response:200, description:"Berhasil")])]
    public function logsPaginated(Request $request){ return $this->success($this->service->paginate($request->limit ?? 10, $request->search, $request->action, $request->from, $request->to)); }
}
