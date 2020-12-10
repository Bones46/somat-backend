<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //var_dump($request->student['student']);

        // $validator = Validator::make($request->all(), [
        //     'parent.*.profile.email' => 'email|required|distinct|unique:'.config('schoolconnect.database.user_table'),
        // ]);
        //
        // $validator->validate();

        return response()->json(['message'=>'Successfully loaded.','user'=>Auth::user()]);
    }
}
