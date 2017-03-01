<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class InfoController extends Controller
{
    protected function home (Request $request)
    {
        $id = $request->get('user_id');

        $home_info = User::where('id', $id)->select('transfer_enable','t','u','d')->first();

        return ['type' => 'success', 'data' => $home_info];
    }
}
