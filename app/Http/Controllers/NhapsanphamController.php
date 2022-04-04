<?php

namespace App\Http\Controllers;

use App\Http\Requests\NhaphangRequest;
use App\Models\BookingDetail;
use App\Models\Category;
use App\Models\ComputerCompany;
use App\Models\DetailProduct;
use App\Models\Nhaphangsanpham;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NhapsanphamController extends Controller
{

    public function index(Request $request)
    {

        $nhap_sanpham = Nhaphangsanpham::orderBy('id', 'desc')->paginate(10);
        return view('admin.nhap_sanpham.index', compact('nhap_sanpham'));
    }

    public function addForm($id)
    {
        $findProduct = Product::find($id);
        if(!empty($findProduct)){
            return view('admin.nhap_sanpham.add', compact('findProduct'));

        }else{
            return redirect(route('error'));
        }
    }
    public function saveAdd(NhaphangRequest $request, $id)
    {

        $model = new Nhaphangsanpham();
        //    $a=json_encode
        if ($request->product_id) {
            $model->product_id = $id;
            $model->fill($request->all());
            $model->save();
            $product = Product::find($id);
            if (!empty($product)) {
                $product->qty = $product->qty + $request->qty;
                $product->save();
            }
        } elseif ($request->detail_product_id) {
            $model->detail_product_id = $id;
            $model->fill($request->all());
            $model->save();
            $detail_product = DetailProduct::find($id);
            if (!empty($detail_product)) {
                $detail_product->qty = $detail_product->qty + $request->qty;
                $detail_product->save();
            }
        }
        return redirect(route('product.index'))->with('success', 'Thêm thành công');
    }

    
}
