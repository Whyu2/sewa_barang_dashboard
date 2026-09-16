<?php
namespace App\Http\Controllers\Api;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

class DashboardController extends BaseController
{
    use ApiResponse;
    public function __construct(protected DashboardService $service) {}

    #[OA\Get(path: "/dashboard-stats", operationId: "getDashboardStats", tags: ["Dashboard"], summary: "Dashboard stats KPI", security: [["bearerAuth"=>[]]], parameters: [new OA\Parameter(name:"from", in:"query", schema: new OA\Schema(type:"string", format:"date")), new OA\Parameter(name:"to", in:"query", schema: new OA\Schema(type:"string", format:"date"))], responses: [new OA\Response(response:200, description:"Berhasil")])]
    public function stats(Request $request) {
        return $this->success($this->service->getStats($request->from, $request->to));
    }

    #[OA\Get(path: "/dashboard-charts", operationId: "getDashboardCharts", tags: ["Dashboard"], summary: "Dashboard charts data", security: [["bearerAuth"=>[]]], parameters: [new OA\Parameter(name:"from", in:"query", schema: new OA\Schema(type:"string")), new OA\Parameter(name:"to", in:"query", schema: new OA\Schema(type:"string"))], responses: [new OA\Response(response:200, description:"Berhasil")])]
    public function charts(Request $request) {
        return $this->success($this->service->getCharts($request->from, $request->to));
    }

    #[OA\Get(path: "/dashboard-tables", operationId: "getDashboardTables", tags: ["Dashboard"], summary: "Dashboard tables", security: [["bearerAuth"=>[]]], parameters: [new OA\Parameter(name:"from", in:"query", schema: new OA\Schema(type:"string", format:"date")), new OA\Parameter(name:"to", in:"query", schema: new OA\Schema(type:"string", format:"date"))], responses: [new OA\Response(response:200, description:"Berhasil")])]
    public function tables(Request $request) {
        return $this->success($this->service->getTables($request->from, $request->to));
    }
}
