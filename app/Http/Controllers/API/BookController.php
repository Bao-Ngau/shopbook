<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function index($pageSize, $currentPage)
    {
        $books = Book::with('author', 'categoryy')->paginate($pageSize, ['*'], 'page', $currentPage);
        return response()->json([
            'message' => 'Lấy dữ liệu sách thành công',
            'books' => $books,
        ]);
    }
    public function getBookByStatus($pageSize, $currentPage)
    {
        $books = Book::with('author', 'categoryy', 'cart')->where('status_book', 1)
            ->where('count_book', '>', 0)
            ->paginate($pageSize, ['*'], 'page', $currentPage);
        return response()->json([
            'message' => 'Lấy dữ liệu sách thành công',
            'books' => $books,
        ]);
    }
    public function add(Request $request)
    {
        try {
            $request->validate([
                'name_book' => 'required|unique:books,name_book',
                'image_book' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|min:10',
                'author_id' => 'required',
                'category_id' => 'required',
                'count_book' => 'numeric',
                'price' => 'required|numeric',
                'sale' => 'required|numeric',
                'status_book' => 'required'
            ], [
                'name_book.required' => 'Nhập trường tên sách',
                'name_book.unique' => 'Tên sách này đã tồn tại',
                'image_book.required' => 'Chọn ảnh cho sách',
                'image_book.image' => 'Không phải là file ảnh',
                'image_book.mimes' => 'Không phải là file(jpeg,png,jpg,gif,svg) ảnh',
                'image_book.max' => 'Ảnh không quá 2MB',
                'description.required' => 'Nhập mô tả ngắn',
                'description.min' => 'Mô tả ngắn ít nhất 10 kí tự',
                'author_id.required' => 'Chọn tác giả',
                'category_id.required' => 'Chọn thể loại',
                'count_book' => 'Không nhập ký tự vào số lượng sách',
                'price.required' => 'Nhập giá tiền',
                'price.numeric' => 'Không nhập kí tự vào giá tiền',
                'sale.required' => 'Nhập số tiền giảm giá sách',
                'sale.numeric' => 'Không nhập kí tự vào giảm giá',
                'status_book.required' => 'Chọn trạng thái của sách'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error
            ], 401);
        }

        $book = Book::create([
            'name_book' => $request['name_book'],
            'image_book' => $this->addIMG($request['image_book']),
            'description' => $request['description'],
            'advantage' => $request['advantage'],
            'author_id' => $request['author_id'],
            'category_id' => $request['category_id'],
            'count_book' => $request['count_book'],
            'price' => $request['price'],
            'sale' => $request['sale'],
            'price_sale' => $request['price_sale'],
            'status_book' => $request['status_book'],
            'create_date_book' => now()->toDateTime(),
            'create_by_book' => $request['create_by_book'],
        ]);
        return response()->json([
            'message' => 'Thêm sách thành công',
            'book' => $book,
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name_book' => 'required|unique:books,name_book,' . $request['id'] . ',id',
                'image_book' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|min:10',
                'author_id' => 'required',
                'category_id' => 'required',
                'count_book' => 'numeric',
                'price' => 'required|numeric',
                'sale' => 'required|numeric',
                'status_book' => 'required',
            ], [
                'name_book.required' => 'Nhập trường tên sách',
                'name_book.unique' => 'Tên sách này đã tồn tại',
                'image_book.image' => 'Không phải là file ảnh',
                'image_book.mimes' => 'Không phải là file(jpeg,png,jpg,gif,svg) ảnh',
                'image_book.max' => 'Ảnh không quá 2MB',
                'description.required' => 'Nhập mô tả ngắn',
                'description.min' => 'Mô tả ngắn ít nhất 10 kí tự',
                'author_id.required' => 'Chọn tác giả',
                'category_id.required' => 'Chọn thể loại',
                'count_book' => 'Không ký tự vào số lượng sách',
                'price.required' => 'Nhập giá tiền',
                'price.numeric' => 'Không nhập kí tự vào giá tiền',
                'sale.required' => 'Nhập số tiền giảm giá sách',
                'sale.numeric' => 'Không nhập kí tự vào giảm giá',
                'status_book.required' => 'Chọn trạng thái của sách'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error
            ], 401);
        }

        $nameFile = [
            'name_book' => $request['name_book'],
            'description' => $request['description'],
            'advantage' => $request['advantage'],
            'author_id' => $request['author_id'],
            'category_id' => $request['category_id'],
            'count_book' => $request['count_book'],
            'price' => $request['price'],
            'sale' => $request['sale'],
            'price_sale' => $request['price_sale'],
            'status_book' => $request['status_book'],
            'updated_date_book' => now()->toDateTime(),
            'updated_at_book' => $request['updated_at_book'],
        ];
        if ($request['image_book']) {
            $nameFile['image_book'] = $this->updateIMG($request['image_book'], $request['id']);
        }

        $book = Book::find($request['id'])->update($nameFile);

        return response()->json([
            'message' => 'Sửa thông tin sách thành công',
            'book' => $book,
        ], 200);
    }

    public function delete(Request $request)
    {
        if ($this->deleteIMG($request['id'])) {
            $book = Book::find($request['id'])->delete();
            return response()->json([
                'message' => 'Xóa thành công sách',
                'book' => $book
            ], 200);
        }
    }
    public function search(Request $request)
    {
        try {
            $request->validate([
                'name_book' => 'required'
            ], [
                'name_book.required' => 'Nhập tên sách để tìm kiếm'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error
            ], 401);
        }
        $books = Book::where('name_book', 'LIKE', '%' . $request['name_book'] . '%')->with('author', 'categoryy')->paginate($request['pageSize'], ['*'], 'page', $request['currentPage']);
        return response()->json([
            'message' => 'Tìm kiếm thành công',
            'books' => $books,
        ], 200);
    }
    public function addIMG($imgFile)
    {
        $imageName = time() . '.' . $imgFile->extension();
        $imgFile->move(env('IMG_BOOK'), $imageName);
        return $imageName;
    }
    public function updateIMG($imgFile, $id)
    {
        if ($this->deleteIMG($id)) {
            return $this->addIMG($imgFile, $id);
        }
    }
    public function deleteIMG($id)
    {
        $nameFileIMG = Book::select('id', 'image_book')->where('id', $id)->first();
        $pathImg = env('IMG_BOOK') . '/' . $nameFileIMG['image_book'];
        if (file_exists($pathImg)) {
            if (!$nameFileIMG['image_book']) {
                return true;
            }
            if (unlink($pathImg)) {
                return true;
            }
        }
        return false;
    }
}
