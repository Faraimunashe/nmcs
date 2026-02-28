<?php

namespace App\Http\Middleware;

use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireStudentInfo
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('admin')) {
            return $next($request);
        }

        if ($user && !Student::where('user_id', $user->id)->exists()) {
            if (!$request->is('attendants/create') && !$request->is('attendants') && !$request->is('logout')) {
                return redirect('/attendants/create');
            }
        }

        return $next($request);
    }
}
