<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SentMail;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SendMailController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|regex:/^([\w]*[\w\.]*(?!\.)@gmail\.com)$/',
            ], [
                'email.required' => 'Nhập trường email',
                'email.regex' => 'Nhập đúng dạng email vd: vidu@gmail.com',
            ]);
        } catch (ValidationException $ex) {
            $errors = $ex->validator->getMessageBag();
            return response()->json(['message' => $errors], 401);
        }
        $random = rand(100000, 999999);
        $mailData = [
            'title' => 'Mã để đổi mật khẩu của bạn là:',
            'body' => $random,
        ];
        $input = $request->all();

        $user = User::where('email', $input['email'])->first();
        if ($user) {
            Mail::to($request['email'])->send(new SentMail($mailData));
            $user->update(
                ['email_code' => $random]
            );
        } else {
            return response()->json([
                'message' => 'Gửi mail thất bại, email chưa được đăng ký',
            ], 401);
        }
        return response()->json([
            'message' => 'Gửi mail thành công',
        ], 200);
    }
}
