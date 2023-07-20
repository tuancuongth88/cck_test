<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

class QueueRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        Queue::pushRaw(json_encode([
            'url' => $request->url(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'user' => $request->user(),
            'ip' => $request->ip(),
        ]));

        return $next($request);
    }
}
