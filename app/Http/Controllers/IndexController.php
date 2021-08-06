<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\User\User;

class IndexController extends Controller
{
    public function index()
    {
        return view('app');
    }
}
