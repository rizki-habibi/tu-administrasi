<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaUtamaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        return redirect()->route($user->getDashboardRoute());
    }
}
