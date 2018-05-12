<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public $goodweIds;

    public function __construct()
    {
        $this->goodweIds = \Config::get('services.goodwe');
    }

    public function show()
    {
        $goodweIds = json_encode(collect($this->goodweIds));
        return view('welcome', compact('goodweIds'));
    }
}
