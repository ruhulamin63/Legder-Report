<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SaleDetail;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class LedgerController extends Controller
{
    public function index()
    {
        try {
            $SaleDetail = SaleDetail::with('product', 'sale')->get();

            return response([
                'message' => 'success',
                'SaleDetails' => $SaleDetail,
            ], 200);

        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()],422);
        }
    }

    //all salesReport
    public function productSaleDetails(){
        try {
            $allSaleDetails = DB::table('sale_details')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->select('sale_details.*', 'products.name', 'sales.date', 'sales.invoice')
            ->get();
            
            $allPurchaseDetails = DB::table('purchase_details')->get();

            //saleDetails
            foreach ($allSaleDetails as $saleDetail) {
                $saleDetails = DB::table('sale_details')
                ->join('products', 'products.id', '=', 'sale_details.product_id')
                ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                ->where('sale_details.product_id', $saleDetail->product_id)
                ->select('sale_details.*', 'products.name', 'sales.date', 'sales.invoice')
                ->get();

                //calculate total quantity and total amount stock in stock out in sale and purchase
                $stockOut[] = $allSaleDetails->sum('quantity');
                $totalAmount[] = $saleDetails->sum('total');
            }

            //purchaseDetails
            foreach ($allPurchaseDetails as $purchaseDetail) {
                $purchaseDetails = DB::table('purchase_details')
                ->join('products', 'products.id', '=', 'purchase_details.product_id')
                ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                ->where('purchase_details.product_id', $purchaseDetail->product_id)
                ->select('purchase_details.*', 'products.name', 'purchases.date', 'purchases.invoice')
                ->get();

                $stockIn[] = $purchaseDetails->sum('quantity');
            }

            // $stock = array_shift($stockIn);

            for($i = 0; $i < count($stockIn); $i++){
                $stock[] = $stockIn[$i] - $stockOut[$i];
                $totalAmount[] = $totalAmount[$i];
            }

           //pagination for saleDetails, stockIn, stockOut, stock, totalAmount
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $itemCollection = collect($allSaleDetails);
            $perPage = 10;
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $allSaleDetails = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            // $allSaleDetails->setPath($request->url());

            //================
            $itemCollection = collect($stockIn);
            $perPage = 10;
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $stockIn = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

            //==============
            $itemCollection = collect($stockOut);
            $perPage = 10;
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $stockOut = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

            //===============
            $itemCollection = collect($stock);
            $perPage = 10;
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $stock = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

            //===============
            $itemCollection = collect($totalAmount);
            $perPage = 10;
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $totalAmount = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            
            return response([
                'message' => 'success',
                'saleDetails' => $allSaleDetails,
                'totalAmount' => $totalAmount,
                'stockIn' => $stockIn,
                'stockOut' => $stockOut,
                'stock' => $stock,
            ], 200);

        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()],422);
        }
    }

    //search product form date to date
    public function reportSales(Request $request){
        // dd($request->all());

        try {
            //saleDetails
            $saleDetails = DB::table('sale_details')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->whereBetween('sales.date', [$request->form_date, $request->to_date])
            ->where('sale_details.product_id', $request->product_id)
            ->select('sale_details.*', 'products.name', 'sales.date', 'sales.invoice')
            ->get();

            //purchaseDetails
            $purchaseDetails = DB::table('purchase_details')
            ->join('products', 'products.id', '=', 'purchase_details.product_id')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->whereBetween('purchases.date', [$request->form_date, $request->to_date])
            ->where('purchase_details.product_id', $request->product_id)
            ->select('purchase_details.*', 'products.name', 'purchases.date', 'purchases.invoice')
            ->get();

            //calculate total quantity and total amount stock in stock out in sale and purchase
            $stockIn = $purchaseDetails->sum('quantity');
            $stockOut = $saleDetails->sum('quantity');
            $stock = $stockIn - $stockOut;
            $totalAmount = $saleDetails->sum('total');

            // dd($totalQuantity, $totalAmount);

            return response([
                'message' => 'success',
                'saleDetails' => $saleDetails,
                'totalAmount' => $totalAmount,
                'stockIn' => $stockIn,
                'stockOut' => $stockOut,
                'stock' => $stock,
            ], 200);

        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()],422);
        }
    }

    //get product info
    public function product($id){
        try {
            $product = Product::find($id);

            return response([
                'message' => 'success',
                'product' => $product,
            ], 200);

        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()],422);
        }
    }
}
