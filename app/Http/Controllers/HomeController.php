<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $typeUser = Auth::user()->typeUser;

            if ($typeUser == 0 || $typeUser == 1) {
                //return view('dashboard');
                return redirect()->route('profile.edit');
            } elseif ($typeUser == 2) {
                return view('abonné.pages.auth.dashboard');
            } else {
                return redirect()->back();
            }
        }

        return redirect('acceuil');
    }

}
