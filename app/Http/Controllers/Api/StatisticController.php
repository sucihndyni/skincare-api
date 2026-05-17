<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function monthlyTransactions()
    {
        $data = DB::table('orders')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total_transaksi')
            )
            ->groupBy('bulan')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Ini adalah data transaksi per bulan 📅',
            'data' => $data
        ]);
    }

    public function yearlyTransactions()
    {
        $data = DB::table('orders')
            ->select(
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('COUNT(*) as total_transaksi')
            )
            ->groupBy('tahun')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Ini adalah data transaksi per tahun 📈',
            'data' => $data
        ]);
    }

    public function totalIncome()
    {
        $total = DB::table('orders')
            ->sum('total_harga');

        return response()->json([
            'success' => true,
            'message' => 'Ini adalah total pendapatan toko kami 💰',
            'total_pendapatan' => $total
        ]);
    }

    public function topProducts()
    {
        $data = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'products.nama_produk',
                DB::raw('SUM(order_details.qty) as total_terjual')
            )
            ->groupBy('products.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(1)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Ini adalah produk terlaris kami 🏆',
            'data' => $data
        ]);
    }
}
