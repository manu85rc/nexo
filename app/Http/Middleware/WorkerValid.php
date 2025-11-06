<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Worker;

class WorkerValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        if (!Worker::where('user_id', auth()->user()->id)->exists()) {

            // return view('home');    
            return redirect('worker');
        }
    
        // Si no tiene perfil


        return $next($request);
    }
}
    
//echo "asd9";exit;