<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProdController extends Controller
{
    public function index($pageSize, $currentPage)
    {
        $products = Product::Paginate($pageSize, ['*'], 'page', $currentPage);
        return response()->json([
            'message' => 'Lấy dữ liệu quảng thành công',
            'products' => $products,
        ], 200);
    }
    public function add(Request $request)
    {
        try {
            $request->validate([
                'image_product' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content_product' => 'required|max:255'
            ], [
                'image_product.required' => 'Chọn ảnh cho sách',
                'image_product.image' => 'Không phải là file ảnh',
                'image_product.mimes' => 'Không phải là file(jpeg,png,jpg,gif,svg) ảnh',
                'image_product.max' => 'Ảnh không quá 2MB',
                'content_product.required' => 'Nhập trường nội dung quảng cáo cho sách',
                'content_product.max' => 'Nội chỉ nhập được 255 kí tự',
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $productAdd = [
            'image_product' => $this->addIMG($request['image_product']),
            'content_product' => $request['content_product'],
        ];
        $product = Product::create($productAdd);
        return response()->json([
            'message' => 'Thêm thành công quảng cáo',
            'product' => $product,
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content_product' => 'required|max:255'
            ], [
                'image_product.image' => 'Không phải là file ảnh',
                'image_product.mimes' => 'Không phải là file(jpeg,png,jpg,gif,svg) ảnh',
                'image_product.max' => 'Ảnh không quá 2MB',
                'content_product.required' => 'Nhập trường nội dung quảng cáo cho sách',
                'content_product.max' => 'Nội chỉ nhập được 255 kí tự',
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $productUpdate = [
            'content_product' => $request['content_product'],
        ];
        if ($request['image_product']) {
            $productUpdate['image_product'] = $this->updateIMG($request['image_product'], $request['id']);
        };
        $product = Product::find($request['id'])->update($productUpdate);
        return response()->json([
            'message' => 'Sửa thông tin quảng cáo thành công',
            'product' => $product
        ], 200);
    }
    public function delete($id)
    {
        if ($this->deleteIMG($id)) {
            $product = Product::find($id)->delete();
            return response()->json([
                'message' => 'Xóa thông quảng cáo thành công',
                'product' => $product
            ], 200);
        }
    }
    public function addIMG($imgFile)
    {
        $imageName = time() . '.' . $imgFile->extension();
        $imgFile->move(env('IMG_PRODUCT'), $imageName);
        return $imageName;
    }
    public function updateIMG($imgFile, $id)
    {
        if ($this->deleteIMG($id)) {
            return $this->addIMG($imgFile);
        }
    }
    public function deleteIMG($id)
    {
        $nameFileIMG = Product::select('id', 'image_product')->where('id', $id)->first();
        $imgPath = env('IMG_PRODUCT') . '/' . $nameFileIMG['image_product'];
        if (!$nameFileIMG['image_product']) {
            return true;
        }
        if (file_exists($imgPath)) {
            if (unlink($imgPath)) {
                return true;
            }
        }

        return false;
    }
}
