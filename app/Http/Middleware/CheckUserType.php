<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, ...$types)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (in_array($user->typeUser, $types)) {
                return $next($request);
            }

            return redirect('/')->with('error', 'Accès refusé.');
            //redirect()->back()->with('error', 'Accès refusé.');
        }

        //return redirect('/login')->with('error', 'Veuillez vous connecter.');
        return redirect('acceuil',['showModal' => 'create'])->with('error', 'Veuillez vous connecter.');
    }

}
