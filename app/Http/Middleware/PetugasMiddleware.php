<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id') || Session::get('role') !== 'petugas') {
            return redirect()->route('login')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}