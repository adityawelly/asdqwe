<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function checkYear()
    {
        $now = Carbon::now();
        dd($now->addYear()->subDay());
    }
}
