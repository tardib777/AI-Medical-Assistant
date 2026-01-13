<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session= $request->user()->Sessions()->where('status','active')->get();
        if($session->isEmpty()){
            $request->user()->Sessions()->create(["status" => "active"]);        
        }
        else{
            foreach($session as $sess){
                if($sess->created_at->diffInMinutes(now())>=30){
                    $sess->status='closed';
                    $sess->save();
                }
            } 
            if($session->where('status','active')->isEmpty()){
                $request->user()->Sessions()->create(["status" => "active"]);
            }          
        }
        return $next($request);
    }
}
