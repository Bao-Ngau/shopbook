<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthorController extends Controller
{
    public function index($pageSize, $currentPage)
    {
        $authors = Author::Paginate($pageSize, ['*'], 'page', $currentPage);
        return response([
            'authors' => $authors,
        ]);
    }
    public function add(Request $request)
    {
        try {
            $request->validate([
                'name_author' => 'required|max:255|unique:authors,name_author',
            ], [
                'name_author.required' => 'Nhập trường tên tác giả',
                'name_author.unique' => 'Tên tác giả đã tồn tại',
                'name_author.max' => 'Tên tác giả chỉ có 255 kí tự'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $author = Author::create([
            'name_author' => $request['name_author'],
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
                'name_author' => 'required|max:255|unique:authors,name_author,' . $request->id . ',id',
            ], [
                'name_author.required' => 'Nhập trường tên tác giả',
                'name_author.max' => 'Tên tác giả chỉ có 255 kí tự',
                'name_author.unique' => 'Tên tác giả đã tồn tại',
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
                'name_author' => $request['name_author'],
            ]);
        } else {
            return response()->json([
                'message' => 'Không có id của tác giả',
            ], 401);
        }
        return response()->json([
            'message' => 'Sửa tác giả thành công',
            'author' => $author
        ], 200);
    }
    public function delete(Request $request)
    {
        $author = Author::find($request['id'])->delete();
        return response()->json([
            'message' => 'Xóa tác giả thành công',
            'author' => $author
        ], 200);
    }
    public function search(Request $request)
    {
        try {
            $request->validate([
                'name_author' => 'required',
            ], [
                'name_author.required' => 'Nhập tên giả để tìm kiếm',
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        };
        $authors = Author::where('name_author', 'LIKE', '%' . $request['name_author'] . '%')->paginate($request['pageSize'], ['*'], 'page', $request['currentPage']);

        return response()->json([
            'message' => 'Tìm kiếm tác giả thành công',
            'authors' => $authors
        ], 200);
    }
    public function getIdAndName()
    {
        $authors = Author::select('id', 'name_author')->get();
        return response()->json([
            'message' => 'Lấy dữ(id,name) liệu thành công',
            'authors' => $authors,
        ], 200);
    }
}
