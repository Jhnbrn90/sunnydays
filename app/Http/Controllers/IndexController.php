<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('welcome', [
            'goodweIds' => json_encode(collect(config('services.goodwe'))),
        ]);
    }
}
