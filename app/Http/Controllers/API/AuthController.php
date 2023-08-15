<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword']]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|regex:/^([\w]*[\w\.]*(?!\.)@gmail\.com)$/',
                'password' => 'required|min:6'
            ], [
                'email.required' => 'Nhập trường email',
                'email.regex' => 'Nhập đúng dạng email vd: vidu@gmail.com',
                'password.required' => 'Nhập trường mật khẩu',
                'password.min' => 'Mật khẩu có ít nhất 6 ký tự'
            ]);
        } catch (ValidationException $ex) {
            $errors = $ex->validator->getMessageBag();
            return response()->json(['message' => $errors], 401);
        }

        $input = $request->all();
        if (!$token = auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
            return response()->json(['error' => 'Nhập sai tài khoản hoặc mật khẩu'], 402);
        }

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'access_token' => $this->createToken($token),
        ], 200);
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name_user' => 'required|between:6,100',
                'email' => 'required|unique:users,email|regex:/^([\w]*[\w\.]*(?!\.)@gmail\.com)$/',
                'password' => 'required|min:6|confirmed'
            ], [
                'name_user.required' => 'Nhập trường tên tài khoản',
                'name_user.betwwen' => 'Trường có chỉ từ 6 đến 100 kí tự',
                'email.required' => 'Nhập trường email',
                'email.unique' => 'Email này đã tồn tại',
                'email.regex' => 'Nhập đúng dạng email vd: vidu@gmail.com',
                'password.required' => 'Nhập trường mật khẩu',
                'password.min' => 'Mật có ít nhất 6 ký tự',
                'password.confirmed' => 'Mật khẩu không khớp'
            ]);
        } catch (ValidationException $ex) {
            $errors = $ex->validator->getMessageBag();
            return response()->json(['message' => $errors], 401);
        }
        $user = User::create([
            'name_user' => $request['name_user'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'created_date_user' => now()->toDateString(),
        ]);
        return response()->json([
            'message' => 'Đăng ký mật khẩu thành công',
        ], 200);
    }
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|regex:/^([\w]*[\w\.]*(?!\.)@gmail\.com)$/',
                'password' => 'required|min:6|confirmed',
                'email_code' => 'required',
            ], [
                'email.required' => 'Nhập trường email',
                'email.regex' => 'Nhập đúng dạng email vd: vidu@gmail.com',
                'password.required' => 'Nhập trường mật khẩu',
                'password.min' => 'Mật khẩu có ít nhất 6 ký tự',
                'password.confirmed' => 'Mật khẩu không khớp',
                'email_code.required' => 'Nhập trường nhập mã code',
            ]);
        } catch (ValidationException $ex) {
            $errors = $ex->validator->getMessageBag();
            return response()->json(['message' => $errors], 401);
        }
        $user = User::where('email', $request['email'])->first();
        if ($request['email_code'] === $user->email_code) {
            $user->update([
                'password' => bcrypt($request['password']),
                'email_code' => '',
            ]);
        } else {
            return response()->json([
                'message' => 'mã code không đúng',
            ], 401);
        }
        return response()->json([
            'message' => 'Đổi mật khẩu thành công',
        ], 200);
    }
    public function userProfile()
    {
        return response()->json(
            auth()->user()
        );
    }
    public function createToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
    public function refresh()
    {
        return $this->createToken(auth()->refresh());
    }
}
