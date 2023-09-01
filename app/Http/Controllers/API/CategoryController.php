<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\deleteRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    function index($pageSize, $currentPage)
    {
        $categorys = Category::Paginate($pageSize, ['*'], 'page', $currentPage);
        return response()->json([
            'message' => 'Lấy dữ liệu thể loại thành công',
            'categorys' => $categorys,
        ], 200);
    }
    function add(Request $request)
    {
        try {
            $request->validate([
                'name_category' => 'required|unique:categorys,name_category|between:1,255'
            ], [
                'name_category.required' => 'Nhập tên thể loại',
                'name_category.unique' => 'Tên này đã tồn tại',
                'name_category.between' => 'Nhập tên thể loại 5->255 kí tự'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $category = Category::create([
            'name_category' => $request['name_category']
        ]);
        return response()->json([
            'message' => 'Thêm thể loại thành công',
            'category' => $category,
        ], 200);
    }
    function update(Request $request)
    {
        try {
            $request->validate([
                'name_category' => 'required|unique:categorys,name_category,' . $request->id . ',id|between:1,255'
            ], [
                'name_category.required' => 'Nhập tên thể loại',
                'name_category.unique' => 'Tên này đã tồn tại',
                'name_category.between' => 'Nhập tên thể loại 5->255 kí tự'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $category = Category::find($request['id']);
        $category->update([
            'name_category' => $request['name_category'],
        ]);
        return response()->json([
            'message' => 'Sửa thể loại thành công',
            'category' => $category,
        ], 200);
    }
    function delete(Request $request)
    {
        $category = Category::find($request['id'])->delete();
        return response()->json([
            'message' => 'Xóa tác giả thành công',
        ], 200);
    }
    function search(Request $request)
    {
        try {
            $request->validate([
                'name_category' => 'required',
            ], [
                'name_category.required' => "Nhập tên tác giả vào ô tìm kiếm",
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $categorys = Category::where('name_category', 'LIKE', '%' . $request['name_category'] . '%')->paginate($request['pageSize'], ['*'], 'page', $request['currentPage']);
        return response()->json([
            'message' => "Tìm kiếm thành công",
            'categorys' => $categorys,
        ], 200);
    }
    public function getIdAndName()
    {
        $categorys = Category::select('id', 'name_category')->get();
        return response()->json([
            'message' => 'Lấy dữ(id,name) liệu thành công',
            'categorys' => $categorys,
        ], 200);
    }
}
