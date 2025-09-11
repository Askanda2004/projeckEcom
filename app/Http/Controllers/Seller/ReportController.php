<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        // วันที่ (ดีฟอลต์ย้อนหลัง 30 วัน)
        $from = $request->date_from ?: now()->subDays(30)->toDateString();
        $to   = $request->date_to   ?: now()->toDateString();

        // สถานะที่นับเป็นยอดขาย
        $saleStatuses = ['paid', 'shipped', 'completed'];

        // -------- Metrics: Product ----------
        $totalProducts = Product::where('seller_id', $sellerId)->count();

        $lowStockThreshold = 5;
        $lowStockCount = Product::where('seller_id', $sellerId)
            ->where('stock_quantity', '<=', $lowStockThreshold)
            ->count();

        $lowStockList = Product::where('seller_id', $sellerId)
            ->where('stock_quantity', '<=', $lowStockThreshold)
            ->orderBy('stock_quantity')
            ->take(10)
            ->get(['product_id','name','stock_quantity']);

        // -------- Metrics: Sales (ช่วงวัน) ----------
        // ยอดขายรวม (total revenue) + จำนวนออเดอร์ + average order value
        $salesAgg = Order::where('seller_id', $sellerId)
            ->whereBetween('order_date', [$from.' 00:00:00', $to.' 23:59:59'])
            ->whereIn('status', $saleStatuses)
            ->selectRaw('COUNT(*) as orders_count, COALESCE(SUM(total_amount),0) as revenue_sum')
            ->first();

        $ordersCount  = (int) ($salesAgg->orders_count ?? 0);
        $revenueSum   = (float) ($salesAgg->revenue_sum ?? 0.0);
        $avgOrder     = $ordersCount > 0 ? $revenueSum / $ordersCount : 0;

        // -------- Daily Sales (สรุปต่อวัน) ----------
        $dailySales = Order::where('seller_id', $sellerId)
            ->whereBetween('order_date', [$from.' 00:00:00', $to.' 23:59:59'])
            ->whereIn('status', $saleStatuses)
            ->selectRaw('DATE(order_date) as d, SUM(total_amount) as total')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        // -------- Top Products (Top 5) ----------
        $topProducts = DB::table('order_items as oi')
            ->join('orders as o', 'o.order_id', '=', 'oi.order_id')
            ->join('products as p', 'p.product_id', '=', 'oi.product_id')
            ->where('o.seller_id', $sellerId)
            ->whereBetween('o.order_date', [$from.' 00:00:00', $to.' 23:59:59'])
            ->whereIn('o.status', $saleStatuses)
            ->select('p.product_id', 'p.name',
                DB::raw('SUM(oi.quantity) as qty'),
                DB::raw('SUM(oi.quantity * oi.price) as revenue')
            )
            ->groupBy('p.product_id','p.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        return view('seller.reports.index', compact(
            'from','to',
            'totalProducts','lowStockCount','lowStockList',
            'ordersCount','revenueSum','avgOrder',
            'dailySales','topProducts','lowStockThreshold'
        ));
    }
}
