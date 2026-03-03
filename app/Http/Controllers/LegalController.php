<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function terms()
    {
        return view('legal.terms');
    }

    public function returns()
    {
        return view('legal.returns');
    }

    public function faq()
    {
        return view('legal.faq');
    }
}
