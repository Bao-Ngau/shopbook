<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return response([
            'authors' => $authors,
        ]);
    }
    public function add(Request $request)
    {
        try {
            $request->validate([
                'name:' => 'required|max:255|unique:author,name',
            ], [
                'name.required' => 'Nhập trường tên tác giả',
                'name.max' => 'Tên tác giả chỉ có 255 kí tự '
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $author = Author::create([
            'name' => $request['name'],
        ]);
        return response()->json([
            'message' => 'Thêm tác giả thành công',
            'author' => $author
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name:' => 'required|max:255|unique:author,name,' . $request->id . ',id',
            ], [
                'name.required' => 'Nhập trường tên tác giả',
                'name.max' => 'Tên tác giả chỉ có 255 kí tự',
                'name.unique' => 'Tên tác giả đã tồn tại',
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        if ($request['id']) {
            $author = Author::find($request['id']);
            $author->update([
                'name' => $request['name'],
            ]);
        } else {
            return response()->json([
                'message' => 'Không có id của tác giả',
            ], 401);
        }
        return response()->json([
            'message' => 'Thêm tác giả thành công',
            'author' => $author
        ], 200);
    }
    public function delete(Request $request)
    {
        if ($request['id'] . isEmpty() && $request['sreach'] . isNull()) {
            return response()->json([
                'message' => 'Không có id của tác giả',
            ], 401);
        }
        $author = Author::find($request['id'])->delete();
        return response()->json([
            'message' => 'Xóa tác giả thành công',
            'author' => $author
        ], 200);
    }
    public function sreach(Request $request)
    {
        if ($request['sreach'] . isEmpty() && $request['sreach'] . isNull()) {
            return response()->json([
                'message' => 'Không có id của tác giả để tìm kiếm',
            ], 401);
        }
        $authors = Author::where('name', 'LIKE', '%' . $request['sreach'] . '%')->get();
        return response()->json([
            'message' => 'Tìm kiếm tác giả thành công',
            'authors' => $authors
        ], 200);
    }
}
