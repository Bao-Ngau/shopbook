<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getIdAndName()
    {
        $roles = Role::all();
        return response()->json([
            'message' => 'Lấy vai trò thành công',
            'roles' => $roles,
        ], 200);
    }
}
