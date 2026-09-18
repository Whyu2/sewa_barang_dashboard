<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Region;
use App\Models\Category;
use App\Models\RentTransaction;
use App\Models\ProductRegion;
use App\Models\ProductLog;

class DashboardService
{
    public function getStats($from = null, $to = null, $regionId = null, $createdBy = null)
    {
        if ($from==='undefined') $from=null;
        if ($to==='undefined') $to=null;
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        $txQuery = RentTransaction::query();
        if ($fromDate) $txQuery->where('rent_date', '>=', $fromDate);
        if ($toDate) $txQuery->where('rent_date', '<=', $toDate);
        if ($regionId) $txQuery->where('region_id', (int) $regionId);
        if ($createdBy) $txQuery->where('created_by', (int) $createdBy);

        return [
            'totalProducts' => Product::count(),
            'totalStockQty' => (int) ProductRegion::sum('qty'),
            'available' => Product::where('status', 'available')->count(),
            'broken' => Product::where('status', 'broken')->orWhere('status', 'damaged')->count(),
            'totalTransactions' => (clone $txQuery)->count(),
            'rented' => (clone $txQuery)->where('status', 'rented')->count(),
            'returned' => (clone $txQuery)->where('status', 'returned')->count(),
            'overdue' => (clone $txQuery)->where('status','overdue')->count(),
            'totalRevenue' => (int) (clone $txQuery)->where('status', 'returned')->selectRaw('COALESCE(SUM(rent_price * qty),0) as sum')->value('sum'),
            'revenueThisMonth' => (int) RentTransaction::where('status', 'returned')->whereMonth('rent_date', now()->month)->whereYear('rent_date', now()->year)->selectRaw('COALESCE(SUM(rent_price * qty),0) as sum')->value('sum'),
            'totalRegions' => Region::count(),
            'totalCategories' => Category::count(),
        ];
    }

    public function getCharts($from = null, $to = null)
    {
        if ($from==='undefined') $from=null;
        if ($to==='undefined') $to=null;
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subMonths(5)->startOfMonth();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfMonth();

        $topProducts = RentTransaction::join('products','products.id','=','rent_transactions.product_id')
            ->whereBetween('rent_transactions.rent_date', [$fromDate, $toDate])
            ->selectRaw('products.name as product, COUNT(*) as trx_count')
            ->groupBy('products.id','products.name')
            ->orderByDesc('trx_count')->limit(5)->get();

        $statusDist = RentTransaction::whereBetween('rent_date', [$fromDate,$toDate])->selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
        $revenuePerRegion = RentTransaction::join('regions','regions.id','=','rent_transactions.region_id')
            ->where('rent_transactions.status','returned')
            ->whereBetween('rent_transactions.rent_date', [$fromDate, $toDate])
            ->selectRaw('regions.name as region, COALESCE(SUM(rent_transactions.rent_price * rent_transactions.qty),0) as revenue')
            ->groupBy('regions.name')->get();
        $productsPerCategory = Category::leftJoin('products','products.category_id','=','categories.id')
            ->selectRaw('categories.name as category, COUNT(products.id) as count')
            ->groupBy('categories.name')->get();

        return [
            'topProducts' => $topProducts,
            'statusDist' => $statusDist,
            'revenuePerRegion' => $revenuePerRegion,
            'productsPerCategory' => $productsPerCategory,
        ];
    }

    public function getTables($from = null, $to = null, $regionId = null, $createdBy = null)
    {
        if ($from==='undefined') $from=null;
        if ($to==='undefined') $to=null;
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
        $recentQuery = RentTransaction::with(['product','region','creator.region'])->orderBy('rent_date','desc');
        $overdueQuery = RentTransaction::with(['product','region','creator.region'])->where('status','overdue')->orderBy('expected_return_date','asc');
        if ($fromDate) { $recentQuery->where('rent_date','>=',$fromDate); $overdueQuery->where('rent_date','>=',$fromDate); }
        if ($toDate) { $recentQuery->where('rent_date','<=',$toDate); $overdueQuery->where('rent_date','<=',$toDate); }
        if ($regionId) { $recentQuery->where('region_id',(int)$regionId); $overdueQuery->where('region_id',(int)$regionId); }
        if ($createdBy) { $recentQuery->where('created_by',(int)$createdBy); $overdueQuery->where('created_by',(int)$createdBy); }
        $recent = $recentQuery->limit(5)->get();
        $overdue = $overdueQuery->limit(5)->get()->map(function($r){ $r->days_overdue = (int) Carbon::parse($r->expected_return_date)->diffInDays(now()); return $r; });
        return ['recent' => $recent, 'overdue' => $overdue];
    }
}
