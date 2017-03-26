<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;

class AdminController extends Controller
{
    /**
     *
     */
    public function package()
    {
        $package = Package::all();

        return ['type' => 'success', 'data' => $package];
    }
}
