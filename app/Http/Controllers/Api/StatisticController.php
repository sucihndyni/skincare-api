<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        $orderStats = DB::table('orders')
            ->select(
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_harga) as revenue')
            )
            ->first();

        $totalSales = DB::table('order_details')->sum('qty') ?: 0;
        $totalProducts = DB::table('products')->count();
        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'products.nama_produk',
                DB::raw('SUM(order_details.qty) as terjual')
            )
            ->groupBy('products.id', 'products.nama_produk')
            ->orderByDesc('terjual')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data statistik berhasil diambil',
            'data' => [
                'revenue'        => (int) ($orderStats->revenue ?? 0),
                'total_orders'   => (int) ($orderStats->total_orders ?? 0),
                'total_sales'    => (int) $totalSales,
                'total_products' => (int) $totalProducts,
                'top_products'   => $topProducts
            ]
        ]);
    }

    public function yearlyStatistics($tahun)
    {
        $data = DB::table('orders')
            ->whereYear('tanggal', $tahun)
            ->select(
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->first();

        return response()->json([
            'message' => 'Statistik transaksi tahunan 📈',
            'tahun' => $tahun,
            'data' => $data
        ]);
    }

    public function monthlyStatistics($tahun, $bulan)
    {
        $data = DB::table('orders')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->select(
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->first();

        return response()->json([
            'message' => 'Statistik transaksi bulanan 📅',
            'tahun' => $tahun,
            'bulan' => $bulan,
            'data' => $data
        ]);
    }

    public function dailyStatistics($tanggal)
    {
        $data = DB::table('orders')
            ->whereDate('tanggal', $tanggal)
            ->select(
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->first();

        return response()->json([
            'message' => 'Statistik transaksi harian 🛒',
            'tanggal' => $tanggal,
            'data' => $data
        ]);
    }

    public function topProducts()
    {
        $data = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.nama_produk',
                'products.brand',
                'products.harga',
                DB::raw('SUM(order_details.qty) as total_terjual')
            )
            ->groupBy('products.id', 'products.nama_produk', 'products.brand', 'products.harga')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return response()->json([
            'message' => 'Daftar produk terlaris 🏆',
            'data' => $data
        ]);
    }
}