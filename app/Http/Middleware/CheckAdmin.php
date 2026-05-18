<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🎯 ពិនិត្យមើលថា បើ User មិនទាន់ Login ឬមិនមែនជា Admin (role !== 'admin') គឺទាត់ចេញភ្លាម
        if (!$request->user() || $request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'សុំទោស! លោកអ្នកគ្មានសិទ្ធិចូលទៅកាន់ផ្នែកនេះទេ (Admin Only)។'
            ], 403); // 403 Forbidden
        }

        return $next($request);
    }
}