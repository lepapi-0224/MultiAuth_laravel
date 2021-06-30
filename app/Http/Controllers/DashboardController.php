<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {        
        if(Auth::user()->hasRole('user'))
        {
            return view('user.home');
        }
        elseif(Auth::user()->hasRole('blogwriter'))
        {
            return view('blogwriter.blogwriterDash');
        }
        elseif(Auth::user()->hasRole('admin'))
        {
            return view('dashboard');
        }
    }

    public function myprofile()
    {
        return view('user.myprofile');
    }

   public function blogwriter()
    {
        return view('blogwriter.postcreate');
    }
}
