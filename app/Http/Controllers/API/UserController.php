<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index($pageSize, $currentPage)
    {
        $users = User::with('role', 'coupon')->paginate($pageSize, ['*'], 'page', $currentPage);
        return response()->json([
            'message' => 'lấy dữ liệu người dùng thành công',
            'users' => $users,
        ]);
    }
    public function add(Request $request)
    {
        // dd($request);
        try {
            $request->validate([
                'name_user' => 'required|between:6,255',
                'email' => 'required|unique:users,email|regex:/^([\w]*[\w\.]*(?!\.)@gmail\.com)$/',
                'password' => 'required|min:6',
                'image_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'role_id' => 'nullable|numeric',
                'phone' => 'nullable|between:8,12|regex:/^[0-9+]+$/',
                'address' => 'nullable|max:255',
            ], [
                'name_user.required' => 'Nhập trường tên tài khoản',
                'name_user.between' => 'Trường có chỉ từ 6 đến 100 kí tự',
                'email.required' => 'Nhập trường email',
                'email.unique' => 'Email này đã tồn tại',
                'email.regex' => 'Nhập đúng dạng email vd: vidu@gmail.com',
                'password.required' => 'Nhập trường mật khẩu',
                'password.min' => 'Mật có ít nhất 6 ký tự',
                'role_id.role_id' => 'Vai trò là số nguyên',
                'role_id.numeric' => 'Vai trò không chứa kí tự',
                'phone.between' => 'Số điện thoại 8->12 số',
                'phone.regex' => 'Không được nhập kí tự',
                'address.max' => 'Đỉa chị của bạn đã quá 255 kí tự'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $userAdd = [
            'name_user' => $request['name_user'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'phone' => $request['phone'],
            'address' => $request['address'],
            'created_date_user' => now()->toDateString(),
        ];
        if ($request['role_id']) {
            $userAdd['role_id'] = $request['role_id'];
        }
        if ($request['image_user']) {
            $userAdd['image_user'] = $this->addIMG($request['image_user']);
        };
        $user = User::create($userAdd);
        return response()->json([
            'message' => 'Thêm thành công',
            'user' => $user,
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name_user' => 'required|between:6,255',
                'email' => 'required|unique:users,email,' . $request->id . ',id|regex:/^([\w]*[\w\.]*(?!\.)@gmail\.com)$/',
                'image_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'role_id' => 'nullable|numeric',
                'phone' => 'nullable|between:8,12|regex:/^[0-9+]+$/',
                'address' => 'nullable|max:255',
            ], [
                'name_user.required' => 'Nhập trường tên tài khoản',
                'name_user.between' => 'Trường có chỉ từ 6 đến 100 kí tự',
                'email.required' => 'Nhập trường email',
                'email.unique' => 'Email này đã tồn tại',
                'email.regex' => 'Nhập đúng dạng email vd: vidu@gmail.com',
                'role_id.numeric' => 'Vai trò không chứa kí tự',
                'phone.between' => 'Số điện thoại 8->12 số',
                'phone.regex' => 'Không được nhập kí tự',
                'address.max' => 'Đỉa chị của bạn đã quá 255 kí tự'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error,
            ], 401);
        }
        $userUpdate = [
            'name_user' => $request['name_user'],
            'email' => $request['email'],
            'role_id' => $request['role_id'],
            'phone' => $request['phone'],
            'address' => $request['address'],
        ];
        if ($request['role_id']) {
            $userAdd['role_id'] = $request['role_id'];
        }
        if ($request['image_user']) {
            $userUpdate['image_user'] = $this->updateIMG($request['image_user'], $request['id']);
        };
        $user = User::find($request['id'])->update($userUpdate);
        return response()->json([
            'message' => 'Sửa thành công',
            'user' => $user,
        ], 200);
    }
    public function delete($id)
    {
        $user = User::find($id)->update([
            'status_user' => 0
        ]);
        return response()->json([
            'message' => "Xóa người dùng thành công",
            'user' => $user,
        ], 200);
    }
    public function search(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required'
            ], [
                'email.required' => 'Nhập email để tìm kiếm'
            ]);
        } catch (ValidationException $ex) {
            $error = $ex->validator->getMessageBag();
            return response()->json([
                'message' => $error
            ], 401);
        }
        $users = User::where('email', 'LIKE', '%' . $request['email'] . '%')->with('coupon', 'role')->paginate($request['pageSize'], ['*'], 'page', $request['currentPage']);
        return response()->json([
            'message' => 'Tìm kiếm thành công',
            'users' => $users,
        ], 200);
    }
    public function addIMG($imgFile)
    {
        $imageName = time() . '.' . $imgFile->extension();
        $imgFile->move(env('IMG_USER'), $imageName);
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
        $nameFileIMG = User::select('id', 'image_user')->where('id', $id)->first();
        $pathImg = env('IMG_USER') . '/' . $nameFileIMG['image_user'];
        if (file_exists($pathImg)) {
            if (!$nameFileIMG['image_user']) {
                return true;
            }
            if (unlink($pathImg)) {
                return true;
            }
        }
        return false;
    }
}
