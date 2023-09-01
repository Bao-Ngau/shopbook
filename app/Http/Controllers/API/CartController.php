<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCartByUser($user)
    {
        $carts = Cart::query()->with('book', 'user', 'pay')
            ->where('user_id', $user)
            ->where('status_cart', 0)
            ->get();

        return response()->json([
            'message' => 'Lấy dữ liệu cart thành công',
            'carts' => $carts,
        ], 200);
    }
    public function add(Request $request)
    {
        $cartCheck = Cart::with('book', 'user', 'pay')->where('book_id', $request['book_id'])
            ->where('user_id', $request['user_id'])
            ->where('status_cart', 0)
            ->first();
        if ($cartCheck) {
            $cartCheck->update([
                'quantity' => $cartCheck['quantity'] + 1,
            ]);
            return response()->json([
                'message' => 'Thêm số lượng thành công',
                'cart' => $cartCheck,
            ], 200);
        } else {
            $cartAdd = [
                'book_id' => $request['book_id'],
                'user_id' => $request['user_id'],
                'total_money' => $request['total_money'],
                'created_date_cart' => now()->toDateTimeString(),
            ];
            if ($request['id']) {
                $cartAdd['id'] = $request['id'];
            }
            if ($request['quantity']) {
                $cartAdd['quantity'] = $request['quantity'];
            }
            if ($request['pay_id']) {
                $cartAdd['pay_id'] = $request['pay_id'];
            }

            $cartAdd = Cart::create($cartAdd);
            $selectAdd = Cart::query()->with('book', 'user', 'pay')->where('id', $cartAdd['id'])->first();
            return response()->json([
                'message' => 'Thêm cart thành công',
                'cart' => $selectAdd,
            ], 200);
        }
    }
    public function updateQuantityById(Request $request)
    {
        if ($request['id'] && $request['quantity']) {
            $cart = Cart::find($request['id'])->update([
                'quantity' => $request['quantity'],
            ]);
        } else {
            return response()->json([
                'message' => 'Không nhận được id và quantity',
            ], 401);
        }
        return response()->json([
            'message' => 'cập nhật quantity thành công',
            'cart' => Cart::find($request['id']),
        ], 200);
    }
    public function delete($id)
    {
        if ($id) {
            $cart = Cart::find($id)->delete();
        } else {
            return response()->json([
                'message' => 'Lỗi ko có id cart để xóa',
            ], 401);
        }
        return response()->json([
            'message' => 'Xóa thành công cart',
            'cart' => $cart,
        ], 200);
    }
}
